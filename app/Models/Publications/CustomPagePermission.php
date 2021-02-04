<?php

namespace App\Models\Publications;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

/**
 * App\Models\Publications\CustomPagePermission
 *
 * @property int $id
 * @property int $role_id
 * @property int $page_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPagePermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPagePermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPagePermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPagePermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPagePermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPagePermission wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPagePermission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomPagePermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CustomPagePermission extends Model
{
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
