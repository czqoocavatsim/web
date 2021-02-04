<?php

namespace App\Models\Training;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;

/**
 * App\Models\Training\ApplicationComment
 *
 * @property int $id
 * @property int $application_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Training\Application $application
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment newQuery()
 * @method static \Illuminate\Database\Query\Builder|ApplicationComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationComment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|ApplicationComment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ApplicationComment withoutTrashed()
 * @mixin \Eloquent
 */
class ApplicationComment extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->content));
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
