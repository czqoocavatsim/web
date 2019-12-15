<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'user_id', 'content', 'link', 'dateTime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function send(User $user, $content, $link)
    {
        $notification = new self;
        $notification->content = $content;
        $notification->user_id = $user->id;
        $notification->link = $link;
        $notification->dateTime = date('Y-m-d H:i:s');
        $notification->save();
    }
}
