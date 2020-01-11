<?php

namespace App\Models\Users;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;

class DiscordBan extends Model
{
    protected $fillable = [
        'user_id', 'reason', 'ban_start_timestamp', 'ban_end_timestamp'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPermanent()
    {
        if (!$this->ban_end_timestamp) return true;
        return false;
    }

    public function banStartPretty()
    {
        return Carbon::create($this->ban_start_timestamp)->toDayDateTimeString().' Zulu';
    }

    public function banEndPretty()
    {
        if($this->isPermanment) return null;
        return Carbon::create($this->ban_end_timestamp)->toDayDateTimeString().' Zulu';
    }

    public function isCurrent()
    {
        if (Carbon::create($this->ban_end_timestamp) > Carbon::now()) return true;
        return false;
    }

    public function durationPretty()
    {
        return Carbon::create($this->ban_end_timestamp)->diffForHumans(Carbon::create($this->ban_start_timestamp));
    }

    public function reasonHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->reason));
    }
}
