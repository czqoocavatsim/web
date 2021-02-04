<?php

namespace App\Models\Training\Instructing\Records;

use App\Models\Training\Instructing\Instructors\Instructor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Parsedown;

/**
 * App\Models\Training\Instructing\Records\OTSSessionPassFailRecord
 *
 * @var ots_session_id The ID of the OTS Session this belongs to.
 * @var result         Enum for the result of the session. (passed/failed/pending)
 * @var assessor_id    The Id of the Assessor (Instructor) who wrote this record.
 * @var report_url     URL to the report for this session.
 * @var remarks        Remarks from assessor.
 * @property int $id
 * @property int $ots_session_id
 * @property string $result
 * @property int $assessor_id
 * @property string|null $report_url
 * @property string|null $remarks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Instructor $assessor
 * @property-read \App\Models\Training\Instructing\Records\OTSSession $session
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord whereAssessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord whereOtsSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord whereReportUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OTSSessionPassFailRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OTSSessionPassFailRecord extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ots_session_id', 'result', 'assessor_id', 'report_url', 'remarks',
    ];

    /**
     * Returns the OTS session this belongs to.
     *
     * @return \App\Models\Training\Instructing\Records\OTSSession
     */
    public function session()
    {
        return $this->belongsTo(OTSSession::class, 'ots_sesion_id');
    }

    /**
     * Returns the asssessor who wrote this record.
     *
     * @return \App\Models\Training\Instructing\Instructors\Instructor
     */
    public function assessor()
    {
        return $this->belongsTo(Instructor::class, 'assessor_id');
    }

    /**
     * Returns the remarks of this record in HTML.
     *
     * @return HtmlString|null
     */
    public function remarksHtml()
    {
        return new HtmlString(app(Parsedown::class)->text($this->remarks));
    }
}
