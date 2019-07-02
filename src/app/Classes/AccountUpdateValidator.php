<?php

namespace App\Classes;

use App\Models\Customer;
use App\Models\Person;
use Framework\Util\Validator;

/**
 * Class AccountUpdateValidator
 *
 * Validates Account update request data.
 *
 * @package App\Classes
 */
class AccountUpdateValidator extends Validator
{
    /**
     * @var array
     */
    protected $fields = [
        'personname',
        'personphone',
        'personemail'
    ];

    /**
     * @return void
     */
    public function handle() {
        $person = Customer::current()->person();

        foreach ($this->fields as $key) {
            $value = $this->data[$key];

            // Check not empty.
            if (!isset($value) || is_null($value) || $value == "") $this->error("$key is required.");

            // Check not too large.
            if (strlen($value) > 50) $this->error("$key is too long.");

            // Check the email doesn't already belong to someone.
            if (
                $key === 'personemail' &&
                $person->personemail !== $this->data[$key] &&
                $found  = Person::where(['personemail' => $this->data[$key]])->first()
            ) $this->error('You cannot use that email address.');
        }
    }
}