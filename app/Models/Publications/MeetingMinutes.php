<?php

namespace App\Models\Publications;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;
use Spatie\Activitylog\Traits\LogsActivity;

class MeetingMinutes extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'title', 'description', 'url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function descriptionHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->description));
    }
}
