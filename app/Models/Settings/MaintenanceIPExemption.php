<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings\MaintenanceIPExemption
 *
 * @property int $id
 * @property string $label
 * @property string $ipv4
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceIPExemption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceIPExemption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceIPExemption query()
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceIPExemption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceIPExemption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceIPExemption whereIpv4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceIPExemption whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MaintenanceIPExemption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MaintenanceIPExemption extends Model
{
    protected $fillable = [
        'id', 'label', 'ipv4',
    ];
}
