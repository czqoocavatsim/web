<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;

class AtcResource extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'url', 'atc_only'
    ];

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->description));
    }
}
