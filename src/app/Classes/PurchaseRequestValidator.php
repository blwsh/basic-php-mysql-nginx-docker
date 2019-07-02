<?php

namespace App\Classes;

use Framework\Util\Validator;

/**
 * Class PurchaseRequestValidator
 *
 * Validates Purchase Request data.
 *
 * @package App\Classes
 */
class PurchaseRequestValidator extends Validator
{
    /**
     * @var array
     */
    protected $fields = [
        'card_number',
        'expiry_month',
        'expiry_year',
        'street',
        'city',
        'postcode'
    ];

    /**
     * @return void
     */
    public function handle() {
        foreach ($this->fields as $key) {
            $value = $this->data[$key];

            // Check not empty
            if (!isset($value) || is_null($value) || $value == "") $this->error("$key is required.");

            // Check not too large
            if (strlen($value) > 50) $this->error("$key is too long.");

            // Check the card is valid.
            if ($key === 'card_number' && !CardIdentifier::identify($value)) $this->error("$key must be a valid card number.");

            // Check the string is a date
            if (($key === 'expiry_month' || $key === 'expiry_year') && !preg_match('/\d{2,4}/', $value)) {
                $this->error("The $key must be a valid date.");
            }
        }
    }
}