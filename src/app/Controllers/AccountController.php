<?php

namespace App\Controllers;

use App\Classes\AccountUpdateValidator;
use App\Models\Address;
use App\Models\Customer;
use App\Models\OnlinePayment;
use Framework\Controller;
use Framework\Request;

class AccountController extends Controller
{
    public function manage() {
        if ($customer = Customer::current()) {
            $addresses = Address
                ::join('fss_CustomerAddress', 'fss_CustomerAddress.addid', '=', 'fss_Address.addid')
                ->where(['fss_CustomerAddress.custid' => $customer->custid])
                ->orderBy(['fss_CustomerAddress.addid'], 'desc')
                ->limit(5)
                ->get();

            $payments = OnlinePayment
                ::where(['custid' => $customer->custid])
                ->join('fss_Payment', 'fss_OnlinePayment.payid', '=', 'fss_Payment.payid')
                ->join('fss_FilmPurchase', 'fss_OnlinePayment.payid', '=', 'fss_FilmPurchase.fpid')
                ->join('fss_Film', 'fss_FilmPurchase.filmid', '=', 'fss_Film.filmid')
                ->join('fss_Rating', 'fss_Film.ratid', '=', 'fss_Rating.ratid')
                ->orderBy(['fss_OnlinePayment.payid'], 'desc')
                ->limit(50)
                ->get();

            return view('account.manage', [
                'customer'  => $customer,
                'payments'  => $payments,
                'addresses' => $addresses
            ]);
        } else {
            redirect(url('/login'));
        }
    }

    public function update(Request $request) {
        $validator = new AccountUpdateValidator($request->get());

        if ($validator->hasErrors()) {
            back(302, ['errors' => $validator->errors()]);
        }

        $customer = Customer::current();
        $person = $customer->person();

        $person->update([
           'personname' => $request->get('personname'),
           'personphone' => $request->get('personphone'),
           'personemail' => $request->get('personemail'),
        ]);

        back();
    }
}