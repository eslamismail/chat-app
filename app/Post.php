<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['body', 'image', 'user_id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'DESC')->with('user');
    }
}
