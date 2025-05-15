<?php

namespace App\Models\Network;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class ExternalController extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'rating', 'division', 'division_name', 'region_code', 'region_name', 'currency', 'monthly_hours'];
        
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id');
    }
}
