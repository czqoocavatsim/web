<?php

namespace App\Models\Training;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;

/**
 * App\Models\Training\ApplicationUpdate
 *
 * @property int $id
 * @property int $application_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string $update_title
 * @property string|null $update_content
 * @property string|null $update_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Training\Application $application
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate newQuery()
 * @method static \Illuminate\Database\Query\Builder|ApplicationUpdate onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate whereApplicationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate whereUpdateContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate whereUpdateTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate whereUpdateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApplicationUpdate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ApplicationUpdate withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ApplicationUpdate withoutTrashed()
 * @mixin \Eloquent
 */
class ApplicationUpdate extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    protected $fillable = [
        'application_id', 'update_title', 'update_content', 'update_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updateContentHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->update_content));
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
