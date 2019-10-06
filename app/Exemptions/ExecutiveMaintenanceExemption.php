<?php

namespace App\Exemptions;

use Auth;
use MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption;

class ExecutiveMaintenanceExemption extends MaintenanceModeExemption
{
    /**
     * Execute the exemption check.
     *
     * @return bool
     */
    public function isExempt()
    {
        if (Auth::check() && Auth::user()->permissions == 4) {
            return true;
        }

        return false;
    }
}
