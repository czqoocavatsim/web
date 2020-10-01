<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class CoreSettings extends Model
{
    use LogsActivity;

    protected $table = 'core_info';

    protected $fillable = [
        'sys_name', 'release', 'sys_build', 'copyright_year', 'banner',
        'emailfirchief', 'emaildepfirchief', 'emailcinstructor',
        'emaileventc', 'emailfacilitye', 'emailwebmaster',
    ];
}
