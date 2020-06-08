<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;
use Spatie\Activitylog\Traits\LogsActivity;

class AtcResource extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id', 'title', 'description', 'url', 'atc_only'
    ];

    public function html()
    {
        return new HtmlString(app(Parsedown::class)->text($this->description));
    }
}
