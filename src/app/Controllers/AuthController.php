<?php

namespace App\Controllers;

use App\Models\Customer;
use function back;
use Framework\Request;
use function redirect;

class AuthController
{
    public function view() {
        return view('login.index');
    }

    public function login(Request $request) {
        $email = $request->get('email');
        $password = $request->get('password');

        if (Customer::attemptLogin($email, $password)) {
            redirect('/account');
        } else {
            back(302, [
                'errors' => [
                    ['Invalid email or password.']
                ]
            ]);
        }
    }

    public function register() {
        return view('login.register');
    }

    public function logout() {
        Customer::logout();
    }
}