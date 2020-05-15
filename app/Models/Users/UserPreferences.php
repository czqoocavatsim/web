<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserPreferences extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'enable_beta_components', 'ui_mode', 'enable_discord_notifications'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
