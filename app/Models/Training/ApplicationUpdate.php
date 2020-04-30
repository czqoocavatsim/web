<?php

namespace App\Models\Training;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;

class ApplicationUpdate extends Model
{

    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'application_id', 'update_title', 'update_content', 'update_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateContentHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->update_content));
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
