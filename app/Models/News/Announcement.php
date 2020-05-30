<?php

namespace App\Models\News;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;

class Announcement extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'user_id', 'target_group', 'title', 'content', 'slug', 'reason_for_sending', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->content));
    }
}
