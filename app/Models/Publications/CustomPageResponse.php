<?php

namespace App\Models\Publications;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Publications\CustomPageResponse
 *
 * @property int $id
 * @property int $page_id
 * @property int $user_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Publications\CustomPage $page
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPageResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPageResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPageResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPageResponse whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPageResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPageResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPageResponse wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPageResponse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPageResponse whereUserId($value)
 * @mixin \Eloquent
 */
class CustomPageResponse extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function page()
    {
        return $this->belongsTo(CustomPage::class, 'page_id');
    }
}
