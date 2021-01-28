<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationReferee extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'application_id', 'referee_full_name', 'referee_email', 'referee_staff_position',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
