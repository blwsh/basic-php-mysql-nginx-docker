<?php

namespace App\Models;

use Framework\Database\Model;

/**
 * Class Customer
 *
 * @property int $custid
 * @property string $custregdate
 * @property string $custendreg
 * @property string $custpassword
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
     * @var array
     */
    protected $hidden = [
        'custpassword'
    ];

    /**
     * @var Customer
     */
    protected static $current;

    /**
     * A relation to the person model.
     */
    public function person() {
        return Person::where(['personid' => $this->custid])->first();
    }

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

    /**
     * Logs the customer out.
     *
     * @param bool|null $redirect - Will not redirect if false, will redirect if
     *                            has value and if null will redirect to /login
     */
    public static function logout($redirect = null) {
        unset($_SESSION['token']);
        self::$current = null;

        if ($redirect !== false) {
            redirect($redirect ?? url('/login'));
        }
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return self::query()->update(['custendreg' => date("Y-m-d")]);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public static function validate(array $data) : array {
        return array_merge(
            self::validateName($data['name']),
            self::validateEmail($data['email']),
            self::validatePhone($data['phone']),
            self::validatePassword($data['password'], $data['confirm_password'])
        );
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public static function validateName(string $name) : array {
        $errorBag = [];
        if ($name) {
            if (strlen($name) <= 3) $errorBag[] = 'Name is too short.';
            if (strlen($name) >= 50) $errorBag[] = 'Name is too long.';
        } else {
            $errorBag[] = 'Name required.';
        }
        return $errorBag;
    }

    /**
     * @param string $email
     *
     * @return array
     */
    public static function validateEmail(string $email) : array {
        $errorBag = [];
        if ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errorBag[] = 'Email is not valid.';
        } else {
            $errorBag[] = 'Email required.';
        }
        return $errorBag;
    }

    /**
     * @param string $phone
     *
     * @return array
     */
    public static function validatePhone(string $phone) : array {
        $errorBag = [];
        if ($phone) {
            if (!preg_match('/^\+*\d{10,11}$/',$phone)) $errorBag[] = 'A phone number can have a plus prefix and must be 10 to 12 digits and contain no spaces.';
        } else {
            $errorBag[] = 'Phone required.';
        }
        return $errorBag;
    }

    /**
     * @param string $password
     * @param string $confirm_password
     *
     * @return array
     */
    public static function validatePassword(string $password, string $confirm_password) : array {
        $errorBag = [];
        if ($password) {
            if ($password !== $confirm_password) $errorBag[] = 'Passwords do not match.';
            if (!preg_match('/[[:upper:]]{1,}/',$password)) $errorBag[] = 'Password must contain at least one uppercase character.';
            if (!preg_match('/[^\w^\d]{1,}/',$password)) $errorBag[] = 'Must contain at least one special character.';
            if (strlen($password) < 7) $errorBag[] = 'Password must be at least 7 characters.';
        } else {
            $errorBag[] = 'Password required.';
        }
        return $errorBag;
    }
}
