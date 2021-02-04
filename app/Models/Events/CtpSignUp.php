<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Events\CtpSignUp
 *
 * @property int $id
 * @property int $user_id
 * @property string $availability
 * @property string $times
 * @property string $submitted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp query()
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp whereAvailability($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp whereTimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CtpSignUp whereUserId($value)
 * @mixin \Eloquent
 */
class CtpSignUp extends Model
{
    //
}
