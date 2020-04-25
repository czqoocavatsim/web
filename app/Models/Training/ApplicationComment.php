<?php

namespace App\Models\Training;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationComment extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->content));
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
