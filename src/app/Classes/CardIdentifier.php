<?php

namespace App\Classes;

/**
 * Class CardIdentifier
 * @package App\Classes
 */
class CardIdentifier
{
    /**
     * @var array
     */
    protected static $cardTypes = [
        "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
        "mastercard" => "/^5[1-5][0-9]{14}$/",
        "amex"       => "/^3[47][0-9]{13}$/",
        "discover"   => "/^6(?:011|5[0-9]{2})[0-9]{12}$/"
    ];

    /**
     * @param $number
     *
     * @return string
     */
    public static function identify($number)
    {
        if (preg_match(self::$cardTypes['visa'], $number)) return 'visa';
        else if (preg_match(self::$cardTypes['mastercard'], $number)) return 'mastercard';
        else if (preg_match(self::$cardTypes['amex'], $number)) return 'amex';
        else if (preg_match(self::$cardTypes['discover'], $number)) return 'discover';
    }

}