<?php

namespace App\Controllers;

use App\Models\Customer;
use App\Models\OnlinePayment;
use Framework\Controller;

class AccountController extends Controller
{
    public function manage() {
        if ($customer = Customer::current()) {
            $payments = OnlinePayment
                ::where(['custid' => $customer->custid])
                ->join('fss_Payment', 'fss_OnlinePayment.payid', '=', 'fss_Payment.payid')
                ->join('fss_FilmPurchase', 'fss_OnlinePayment.payid', '=', 'fss_FilmPurchase.fpid')
                ->join('fss_Film', 'fss_FilmPurchase.filmid', '=', 'fss_Film.filmid')
                ->join('fss_Rating', 'fss_Film.ratid', '=', 'fss_Rating.ratid')
                ->limit(5)
                ->get();

            return view('account.manage', [
                'customer' => $customer,
                'payments' => $payments
            ]);
        } else {
            redirect('/login');
        }
    }
}