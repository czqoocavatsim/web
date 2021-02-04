<?php

namespace App\Models\Training;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\HtmlString;
use Parsedown;

/**
 * App\Models\Training\Application
 *
 * @property int $id
 * @property string|null $reference_id
 * @property int $user_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $applicant_statement
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Training\ApplicationComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Training\ApplicationReferee[] $referees
 * @property-read int|null $referees_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Training\ApplicationUpdate[] $updates
 * @property-read int|null $updates_count
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Application newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Application newQuery()
 * @method static \Illuminate\Database\Query\Builder|Application onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Application query()
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereApplicantStatement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Application whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Application withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Application withoutTrashed()
 * @mixin \Eloquent
 */
class Application extends Model
{
    use SoftDeletes;

    protected $hidden = ['id'];

    /*
    Application statuses

    0 = pending
    1 = accepted
    2 = denied
    3 = withdrawn
    4 = deleted
    */

    public function statusBadgeHtml()
    {
        switch ($this->status) {
            case 0:
                return ['html' => '<i class="far fa-clock mr-2"></i>&nbsp;Pending', 'class' => 'orange white-text'];
            break;
            case 1:
                return ['html' => '<i class="fas fa-check mr-3"></i>&nbsp;Accepted', 'class' => 'green white-text'];
            break;
            case 2:
                return ['html' => '<i class="fas fa-times mr-3"></i>&nbsp;Rejected', 'class' => 'red white-text'];
            break;
            case 3:
                return ['html' => '<i class="fas fa-times mr-3"></i>&nbsp;Withdrawn', 'class' => 'grey white-text'];
            break;
            case 4:
                return ['html' => 'Deleted', 'class' => 'grey white-text'];
            break;
            default:
                return $this->status;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicantStatementHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->applicant_statement));
    }

    public function comments()
    {
        return $this->hasMany(ApplicationComment::class);
    }

    public function referees()
    {
        return $this->hasMany(ApplicationReferee::class);
    }

    public function updates()
    {
        return $this->hasMany(ApplicationUpdate::class);
    }
}
