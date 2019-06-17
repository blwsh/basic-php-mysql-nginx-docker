<?php

namespace App\Controllers;

use App\Classes\DB;
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
    public function login(Request $request) {
        if (Customer::current()) redirect('/');
        return view('login.index', ['next' => $request->get('next')]);
    }

    /**
     * @param Request $request
     */
    public function loginAction(Request $request) {
        $email = $request->get('email');
        $password = $request->get('password');

        if (Customer::attemptLogin($email, $password)) {
            $next = $request->get('next');

            if ($next && urldecode($next) && !preg_match('/^(http(s)*:)*\/\//', $next, $match)) {
                redirect($next);
            } else {
                redirect(url('/account'));
            }
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
        $old = ['name' => $request->get('name'), 'email' => $request->get('email'), 'phone' => $request->get('phone')];
        $errors = Customer::validate($request->get());

        if (count($errors) < 1) {
            // Find the person where email is one specified in request and
            // and redirect back with errors if the email is already taken.
            if (Person::where(['personemail' => $request->get('email')])->first()) {
                back(302, ['old' => $old, 'errors' => [htmlentities($request->get('email')) . ' is already taken.']]);
            }

            // Start database transactions.
            DB::beginTransaction();

            // Tty to insert data.
            try {
                // Create the person record.
                Person::create($person = array_map(function($item) { return htmlentities($item); }, [
                    'personname' => $request->get('name'),
                    'personemail' => $request->get('email'),
                    'personphone' => $request->get('phone'),
                ]));

                // Get the inserted person id using filled model data.
                $person = Person::where($person)->first();

                // Insert related customer record for customer.
                Customer::insert([
                    'custid'       => $person->personid,
                    'custregdate'  => date("Y-m-d"),
                    'custendreg'   => date("Y-m-d"),
                    'custpassword' => password_hash($request->get('password'), PASSWORD_BCRYPT)
                ]);
            } catch (Exception $e) {
                // Rollback db.
                DB::rollback();

                // Show error.
                isDebug() ? error($e) : back(302, ['errors' => [$e->getMessage()]]);
            }

            // Commit the changes.
            DB::commit();
        } else {
            back(302, ['errors' => $errors, 'old' => $old]);
        }

        // Try to login the user using provided email and password and redirect to
        // account page if success otherwise redirect back with errors if fail to login.
        if (Customer::attemptLogin($request->get('email'), $request->get('password'))) {
            redirect(url('/account'));
        } else {
            back(302, ['errors' => ['Successfully registered but unable to log you in.']]);
        }
    }

    /**
     * @return void
     */
    public function logout() {
        Customer::logout();
    }
}