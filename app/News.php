<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'id', 'title', 'user_id', 'show_author', 'image', 'content', 'summary', 'published', 'edited', 'visible', 'email_level', 'certification'
    ];

    /*
     * * Return who posted the article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function published_pretty()
    {
        return Carbon::create($this->published)->toDayDateTimeString();
    }

    public function edited_pretty()
    {
        return Carbon::create($this->edited)->toDayDateTimeString();
    }
}
