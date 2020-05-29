<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class CoreSettings extends Model
{
    protected $table = 'core_info';

    protected $fillable = [
        'sys_name', 'release', 'sys_build', 'copyright_year', 'banner',
        'emailfirchief', 'emaildepfirchief', 'emailcinstructor',
        'emaileventc', 'emailfacilitye', 'emailwebmaster',
    ];
}
