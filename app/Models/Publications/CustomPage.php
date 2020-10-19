<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CustomPage extends Model
{
    //

    public function permissions()
    {
        return $this->hasMany(CustomPagePermission::class, 'page_id');
    }

    public function responses()
    {
        return $this->hasMany(CustomPageResponse::class, 'page_id');
    }

    public function userHasResponded()
    {
        if (CustomPageResponse::where('page_id', $this->id)->where('user_id', Auth::id())->first())
        {
            return false;
        }
        return false;
    }
}
