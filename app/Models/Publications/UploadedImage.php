<?php

namespace App\Models\Publications;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class UploadedImage extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'user_id', 'path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
