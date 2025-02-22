<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CTPDates extends Model
{
    use HasFactory;

    protected $table = "ctp_dates";
    protected $fillable = ['id', 'edition', 'oca_start', 'oca_end'];
        
}
