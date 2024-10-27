<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatusController extends Controller
{
    public function NumberOfAllUsers(){
        return Cache::tags(['users','posts'])->remember('NumberOfAllUsers', 3600, function(){
             return User::all()->count();
         });
    }

    public function NumberOfAllPosts()
    {
      return  Cache::tags(['users','posts'])->remember('NumberOfAllPosts', 3600, function(){
            return Post::all()->count();
        });
    }

    public function NumberOfUsersWithZeroPosts()
    {
        return  Cache::tags(['users','posts'])->remember('NumberOfUsersWithZeroPosts', 3600, function(){
            return User::whereDoesntHave('posts')->get();
        });
    }


}
