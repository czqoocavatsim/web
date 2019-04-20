<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{ 
    protected $fillable = [
        'user_id', 'qualification', 'email'
    ];

    /*
     * * Return who posted the article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function students(){
        return $this->hasMany('App\Student');
    }
}
