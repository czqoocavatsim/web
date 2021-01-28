<?php

namespace App\Models\Settings;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class RotationImage extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id', 'path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
