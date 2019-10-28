<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    protected $fillable = ['name','user_id'];
    public $timestamps = false;

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
