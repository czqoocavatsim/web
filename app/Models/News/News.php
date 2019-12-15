<?php

namespace App\Models\News;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;
use App\Models\Users\User;

class News extends Model
{
    protected $fillable = [
        'id', 'title', 'user_id', 'show_author', 'image', 'content', 'summary', 'published', 'edited', 'visible', 'email_level', 'certification', 'slug'
    ];

    /*
     * * Return who posted the article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function published_pretty()
    {
        $t = Carbon::create($this->published);
        return $t->day . ' ' . $t->monthName . ' ' . $t->year;
    }

    public function edited_pretty()
    {
        if (!$this->edited)
        {
            return null;
        }
        return Carbon::create($this->edited)->toDayDateTimeString();
    }

    public function author_pretty()
    {
        if (!$this->show_author)
        {
            return 'Gander Oceanic Staff';
        }

        return $this->user->fullName('FLC');
    }

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->content));
    }
}
