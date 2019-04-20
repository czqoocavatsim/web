<?php namespace App\Exemptions;

use MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption;
use Auth;
class ExecutiveMaintenanceExemption extends MaintenanceModeExemption
{
    /**
     * Execute the exemption check
     *
     * @return bool
     */
    public function isExempt()
    {
        if (Auth::check() && Auth::user()->permissions == 4) 
        { 
            return true; 
        }
        return false;
    }
}