<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\Publications\CustomPage
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $content
 * @property string|null $description
 * @property string|null $thumbnail
 * @property int $response_form_enabled
 * @property string|null $response_form_email
 * @property string|null $response_form_title
 * @property string|null $response_form_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Publications\CustomPagePermission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Publications\CustomPageResponse[] $responses
 * @property-read int|null $responses_count
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereResponseFormDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereResponseFormEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereResponseFormEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereResponseFormTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomPage extends Model
{
    protected $fillable = ['slug'];

    public function permissions()
    {
        return $this->hasMany(CustomPagePermission::class, 'page_id');
    }

    public function responses()
    {
        return $this->hasMany(CustomPageResponse::class, 'page_id');
    }

    public function userHasResponded()
    {
        if (CustomPageResponse::where('page_id', $this->id)->where('user_id', Auth::id())->first()) {
            return false;
        }

        return false;
    }
}
