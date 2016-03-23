<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'blogs';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content'
    ];


    public function with_users()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function with_comments()
    {
        return $this->hasMany('App\Comment', 'blog_id');
    }

}
