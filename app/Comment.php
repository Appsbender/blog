<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = [
        'user_id',
        'blog_id',
        'content'
    ];


    public function with_users(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function with_blog()
    {
        return $this->belongsTo('App\Blog', 'blog_id');
    }

}
