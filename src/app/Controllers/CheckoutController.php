<?php

namespace App\Controllers;

use App\Classes\DB;
use App\Classes\PurchaseRequestValidator;
use App\Classes\CardIdentifier;
use App\Models\Address;
use App\Models\Basket;
use App\Models\CardPayment;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\DVDStock;
use App\Models\FilmPurchase;
use App\Models\OnlinePayment;
use App\Models\OnlinePurchase;
use App\Models\Payment;
use DateTime;
use Exception;
use Framework\Http\Controller;
use Framework\Http\Request;
use Framework\Util\Arr;

/**
 * Class CheckoutController
 * @package App\Controllers
 */
class CheckoutController extends Controller
{
    /**
     * @return \Framework\Http\View
     */
    public function overview() {
        if (Basket::items()) {
            return view('checkout.overview', ['items' => Basket::items(), 'subtotal' => Basket::subtotal()], false);
        }

        return view('checkout.empty', [], false);
    }

    /**
     * @return \Framework\Http\View
     */
    public function complete() {
        // Get current customer.
        $customer = Customer::current();

        // If customer is not logged in, send them to login page.
        if (!$customer) {
            redirect(url('/login?next=' . urlencode(url('/checkout/complete'))), 302, ['errors' => ['Please login before continuing.']]);
        }

        if (Basket::items()) {
            return view('checkout.complete', ['items' => Basket::items(), 'subtotal' => Basket::subtotal(), 'customer' => Customer::current()], false);
        }

        return view('checkout.empty', [], false);
    }

    /**
     * @param Request $request
     */
    public function submit(Request $request) {
        // Array used to store errors. If not empty, the inserts will not run
        // And the user will be redirected back.
        $errors = [];

        // Get current customer.
        $customer = Customer::current();

        // If customer is not logged in, send them to login page.
        if (!$customer) {
            redirect(url('/login?next=' . url('/checkout/complete')));
        }

        // Validate data
        $validator = new PurchaseRequestValidator($request->get());

        // Check for errors
        if ($validator->hasErrors()) {
            back(302, ['errors' => $validator->errors()]);
        }

        // Get basket items
        $basketItems = Basket::items();

        if (!$basketItems) {
            back(302, ['errors' => ['Your basket is currently empty.']]);
        }

        // Map dvd stocks in to array where key is id and value is quantity.
        $currentStock = DVDStock::where(['shopid' => 1])->whereIn('filmid', Arr::pluck($basketItems,'item.attributes.filmid'))->get();
        $currentStock = array_combine(
            Arr::pluck($currentStock, 'attributes.filmid'),
            Arr::pluck($currentStock, 'attributes.stocklevel')
        );

        // Validate all basket items do not exceed stock levels before
        // continuing with purchase.
        foreach ($basketItems as $item) {
            if ($item->quantity > $currentStock[$item->item->filmid]) {
                $errors[] = "Unable to complete order. Not enough of {$item->item->filmtitle} in stock. Please remove " . ($item->quantity - $currentStock[$item->item->filmid]) . " copy of {$item->item->filmtitle} from basket and try again.";
            }
        }

        // Find current address.
        $address = Address
            ::join('fss_CustomerAddress', 'fss_CustomerAddress.addid', '=', 'fss_Address.addid')
            ->where([
                'fss_CustomerAddress.custid' => $customer->custid,
                'fss_Address.addstreet' => $request->get('street'),
                'fss_Address.addcity' => $request->get('city'),
                'fss_Address.addpostcode' => $request->get('postcode')
            ])
            ->orderBy(['fss_CustomerAddress.addid'], 'DESC')
            ->first();

        // If no current address found, create one.
        if (!$address) {
            // Create the film.
            Address::create($addressValues = [
                'addstreet' => ucfirst($request->get('street')),
                'addcity' => ucfirst($request->get('city')),
                'addpostcode' => ucfirst($request->get('postcode'))
            ]);

            // Then get it from db.
            $address = Address::where($addressValues)->first();

            // Add the address to customer.
            CustomerAddress::create([
                'custid' => $customer->custid,
                'addid' => $address->addid
            ]);

            // If still no address then nothing we can do.
            if (!$address) {
                $errors[] = 'Unable to create address.';
            }
        }

        // Redirect user back with errors if errors array is not empty.
        if (!empty($errors)) back(302, ['errors' => $errors]);

        // Begin a database transaction. Will rollback if anything in the try-catch
        // block throws an exception.
        DB::beginTransaction();

        try {
            foreach ($basketItems as $item) {
                foreach (range(1, $item->quantity) as $i) {
                    Payment::create([
                        'shopid'  => 1, // Online
                        'paydate' => date('Y-m-d'),
                        'amount' => config('base_price', 9.99),
                        'ptid' => 2 // Card
                    ]);

                    $payid = DB::lastInsertId();

                    CardPayment::create([
                        'payid' => $payid,
                        'cno' => $card = $request->get('card_number'),
                        'ctype' => CardIdentifier::identify($card) ? CardIdentifier::identify($card) : 'err',
                        'cexpr' => DateTime::createFromFormat('Y-m', $request->get('expiry_year') . '-' . $request->get('expiry_month'))->format('m:y')
                    ]);

                    OnlinePayment::create([
                        'payid' => $payid,
                        'custid' => $customer->custid
                    ]);

                    FilmPurchase::create($filmPurchase = [
                        'payid' => $payid,
                        'filmid' => $item->item->filmid,
                        'shopid' => 1,
                        'price' => config('base_price', 9.99)
                    ]);

                    OnlinePurchase::create([
                        'fpid' => $payid,
                        'addid' => $address->addid
                    ]);
                }

                DVDStock
                    ::where([
                        'shopid' => 1,
                        'filmid' => $item->item->filmid
                    ])
                    ->update([
                        'stocklevel' => $currentStock[$item->item->filmid] - $item->quantity
                    ]);
            }
        } catch (Exception $exception) {
            DB::rollback();
            isDebug() ? error($exception) : back(302, ['errors' => ['There was an error completing your purchase. You have not been charged. Please try again later.']]);
        }

        // Commit the changes
        DB::commit();

        // Clear the basket.
        Basket::clear();

        // Redirect the user to the success page.
        redirect(url('/checkout/success'));
    }

    /**
     * @return \Framework\Http\View
     */
    public function success() {
        return view('checkout/success', [], false);
    }
}