<?php

namespace App\Models\Training;

use App\Models\News\Announcement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ControllerAcknowledgement extends Model
{
    protected $guarded = [];

    public function getAcknowledgement()
    {
        return $this->belongsTo(Announcement::class, 'announcement_id', 'id');
    }
}
