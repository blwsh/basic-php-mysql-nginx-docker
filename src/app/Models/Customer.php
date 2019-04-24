<?php

namespace App\Models;

use function explode;
use Framework\Model;
use function redirect;

/**
 * Class User
 */
class Customer extends Model {
    /**
     * @var string
     */
    protected $table = 'fss_Customer';

    /**
     * @var string
     */
    protected $primaryKey = 'custid';

    /**
     * @var Customer
     */
    protected static $current;

    /**
     * @return Customer|Model|bool
     */
    public static function current() {
        // Use cached if set.
        if (self::$current) self::$current;

        // Otherwise try to find current use using token.
        if ($token = $_SESSION['token']) {
            $array    = explode(':', openssl_decrypt($token, 'AES-128-ECB', config('app_key')), 2);
            $email    = $array[0];
            $password = $array[1];

            $customer = self
                ::join('fss_Person', 'custid', '=', 'personid')
                ->where(['personemail' => $email])
                ->first();

            if ($customer->custpassword === $password) {
                self::$current = $customer;
                return $customer;
            }
        }

        return false;
    }

    /**
     * @param $email
     * @param $password
     *
     * @return bool
     */
    public static function attemptLogin($email, $password)
    {
        $customer = self
            ::join('fss_Person', 'custid', '=', 'personid')
            ->where(['personemail' => $email])
            ->first();

        if (password_verify($password, $customer->custpassword)) {
            $_SESSION['token'] = openssl_encrypt("$customer->personemail:$customer->custpassword", 'AES-128-ECB', config('app_key'));
            return true;
        }

        return false;
    }

    public static function logout() {
        unset($_SESSION['token']);
        self::$current = null;
        redirect('/login');
    }
}
