<?php

namespace App\Controllers;

use Exception;
use Framework\Request;
use App\Models\Person;
use App\Models\Customer;

/**
 * Class AuthController
 * @package App\Controllers
 */
class AuthController
{
    /**
     * @return \Framework\View
     */
    public function login() {
        if (Customer::current()) redirect('/');
        return view('login.index');
    }

    /**
     * @param Request $request
     */
    public function loginAction(Request $request) {
        $email = $request->get('email');
        $password = $request->get('password');

        if (Customer::attemptLogin($email, $password)) {
            redirect('/account');
        } else {
            back(302, [
                'old'    => [ 'email' => $request->get('email') ],
                'errors' => ['Invalid email or password.']
            ]);
        }
    }

    /**
     * @return \Framework\View
     */
    public function register() {
        if (Customer::current()) redirect('/');
        return view('login.register');
    }

    /**
     * @param Request $request
     */
    public function registerAction(Request $request) {
        $errors = Customer::validate($request->get());

        if (count($errors) < 1) {
            if (Person::where(['personemail' => $request->get('email')])->first()) {
                back(302, ['old' => $request->get(), 'errors' => [htmlentities($request->get('email')) . ' is already taken.']]);
            }

            Person::create($person = array_map(function($item) { return htmlentities($item); }, [
                'personname' => $request->get('name'),
                'personemail' => $request->get('email'),
                'personphone' => $request->get('phone'),
            ]));

            $person = Person::where($person)->first();

            try {
                $customer = Customer::query();

                $customer->insert([
                    'custid'       => $person->personid,
                    'custregdate'  => date("Y-m-d"),
                    'custendreg'   => null,
                    'custpassword' => password_hash($request->get('password'), PASSWORD_BCRYPT)
                ]);
            } catch (Exception $exception) {
                $person->delete();
                back(302, ['errors' => [$exception->getMessage()]]);
            }

            if (Customer::attemptLogin($request->get('email'), $request->get('password'))) {
                redirect('/account');
            } else {
                back(302, ['errors' => ['Successfully registered but unable to log you in.']]);
            }
        } else {
            back(302, ['old' => $request->get(), 'errors' => $errors]);
        }
    }

    /**
     *
     */
    public function logout() {
        Customer::logout();
    }
}