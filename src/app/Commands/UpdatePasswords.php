<?php

namespace App\Commands;

use Framework\Command;
use App\Models\Customer;

class UpdatePasswords extends Command
{
    /**
     * @return mixed
     */
    public function handle()
    {
        $customers = Customer::get();

        $this->info('Encrypting ' . count($customers) . ' customer passwords');

        foreach ($customers as $customer) {
            if (preg_match('/^\$2[ayb]\$.{56}$/', $customer->custpassword)) {
                $this->info('It looks like ' . $customer->custid . ' already has an encrypted password. Skipping.' );
                continue;
            }

            $newPassword = password_hash($customer->custpassword, PASSWORD_BCRYPT);

            if ($customer->update(['custpassword' => $newPassword])) {
                $this->info($newPassword);
            } else {
                $this->error('Failed to set new password for '. $customer->custid);
            }
        }

        $this->info('Done!');
    }
}