<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['image', 'user_id', 'post_id', 'text'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
