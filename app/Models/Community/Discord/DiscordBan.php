<?php

namespace App\Models\Community\Discord;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;

class DiscordBan extends Model
{
    protected $hidden = ['id'];

    protected $fillable = [
        'user_id', 'moderator_id', 'reason', 'start_time', 'end_time', 'discord_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_id');
    }

    public function reasonHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->reason));
    }

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];
}
