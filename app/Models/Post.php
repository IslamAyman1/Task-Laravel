<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['name','body','coverImage','pinned','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
    protected static function booted()
    {
        static::updating(function () {
            Cache::tags('posts')->flush();
        });
    }
}
