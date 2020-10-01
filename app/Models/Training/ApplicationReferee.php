<?php

namespace App\Models\Training;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;

class ApplicationReferee extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'application_id', 'referee_full_name', 'referee_email', 'referee_staff_position'
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
