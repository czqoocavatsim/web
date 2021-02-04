<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\News\CarouselItem
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CarouselItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarouselItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CarouselItem query()
 * @mixin \Eloquent
 */
class CarouselItem extends Model
{
    protected $table = 'carousel';

    protected $fillable = [
        'image_url', 'caption', 'caption_url',
    ];
}
