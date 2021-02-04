<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Times of the year to remove members
/**
 * App\MemberRemovalTime
 *
 * @property int $id
 * @property int $day
 * @property int $month
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MemberRemovalTime newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberRemovalTime newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberRemovalTime query()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberRemovalTime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberRemovalTime whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberRemovalTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberRemovalTime whereMonth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MemberRemovalTime whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MemberRemovalTime extends Model
{
    protected $fillable = [
        'id', 'day', 'month',
    ];
}
