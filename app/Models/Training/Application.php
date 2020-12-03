<?php

namespace App\Models\Training;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Parsedown;
use Illuminate\Database\Eloquent\SoftDeletes;

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
                return array('html' => '<i class="far fa-clock mr-2"></i>&nbsp;Pending', 'class' => 'orange white-text');
            break;
            case 1:
                return array('html' => '<i class="fas fa-check mr-3"></i>&nbsp;Accepted', 'class' => 'green white-text');
            break;
            case 2:
                return array('html' => '<i class="fas fa-times mr-3"></i>&nbsp;Rejected', 'class' => 'red white-text');
            break;
            case 3:
                return array('html' => '<i class="fas fa-times mr-3"></i>&nbsp;Withdrawn', 'class' => 'grey white-text');
            break;
            case 4:
                return array('html' => 'Deleted', 'class' => 'grey white-text');
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
