<?php

namespace App\Models\Settings;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\AuditLogEntry
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property int $affected_id
 * @property string $time
 * @property int $private
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $affectedUser
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry whereAffectedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry wherePrivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditLogEntry whereUserId($value)
 * @mixin \Eloquent
 */
class AuditLogEntry extends Model
{
    protected $fillable = [
        'user_id', 'action', 'affected_id', 'time', 'private',
    ];

    public static function insert(User $user, $message, User $affected_user, $private)
    {
        $log = new self();
        $log->action = $message;
        $log->user_id = $user->id;
        $log->affected_id = $affected_user->id;
        $log->time = date('Y-m-d H:i:s');
        $log->private = $private;
        $log->save();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function affectedUser()
    {
        return $this->belongsTo(User::class, 'affected_id');
    }
}
