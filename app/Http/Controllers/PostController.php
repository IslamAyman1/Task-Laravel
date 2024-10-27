<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use App\Traits\UserTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use UserTrait;
    public function index()
    {
        $user = Auth::user();
        $posts = Post::where('user_id',$user->id)->get();
        return PostResource::collection($posts);
    }
    public function store(Request $request)
    {
        $validation = Validator::make(
            [
                 'name' => $request->name,
                 'body' => $request->body,
                 'coverImage' => $request->coverImage,
                 'pinned' => $request->pinned,
            ]
            ,[
                 'name' => 'required|string',
                 'body' => 'required|string',
                 'coverImage' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                 'pinned' => 'required|boolean',
            ]);
        if($validation->fails()){
            return response()->json($validation->errors(), 400);
        }
        $fileName = time().$request->coverImage->getClientOriginalExtension();
        $imagePath = $request->coverImage->storeAs('coverImages',$fileName,'userPhoto');
        $post = Post::create([
            'name' => $request->name,
            'body' => $request->body,
            'coverImage' => $imagePath,
            'pinned' => $request->pinned,
            'user_id' => Auth::user()->id
        ]);

        return PostResource::make($post);
    }
    public function singlePost(Request $request)
    {
        $user = Auth::user();
        $userPost = $user->posts->where('id',$request->id)->first();
        if(!$userPost){
            return response()->json([
                'message' => 'Post not found'
            ]);
        }
        return PostResource::make($userPost);
    }
    public function update(Request $request){
        $rules = [
            'name' => 'required|string',
            'body' => 'required|string',
            'coverImage' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'pinned' => 'required|boolean',
        ];
        $validation = Validator::make([
            'name' => $request->name,
            'body' => $request->body,
            'pinned' => $request ->pinned
        ],$rules);

        if($validation->fails()){
            return response()->json($validation->errors(), 400);
        }
          $userPost = Auth::user()->posts;

            foreach($userPost as $post){
                if($request->coverImage != null){
                    if(File::exists(public_path('userPhoto/'.$post->coverImage)))
                      File::delete(public_path('userPhoto/'.$post->coverImage));
                    $fileName = time().$request->coverImage->getClientOriginalExtension();
                    $path =  $request->coverImage->storeAs('coverImages',$fileName,'userPhoto');

                    $post->where('id',$request->id)->first()->update([
                        'name' => $request->name,
                        'body' => $request->body,
                        'coverImage' =>  $path,
                        'pinned' => $request->pinned
                    ]);
                    return $this->SendResponse("Post updated successfully with photo",200);

                }

                $post->where('id',$request->id)->first()->update([
                       'name' => $request->name,
                       'body' => $request->body,
                       'coverImage' => $post->coverImage,
                       'pinned' => $request->pinned
                ]);
                return $this->SendResponse("Post updated successfully without photo",200);

            }

    }
    public function delete(Request $request)
    {
        $user = Auth::user();
        $post = User::where('id',$user->id)->first()->posts;
        $deletedPost = $post->where('id' , $request->id)->first();
            if(!$deletedPost){
                return $this->SendResponse("Post Not Found" , 404);

            }
            $deletedPost->delete();
            return $this->SendResponse("Post deleted successfully");
    }
    public function getDeletedPosts(){
       $user =  Auth::user();
       $userPost = Post::onlyTrashed()->where('user_id',$user->id)->get();
       if(!$userPost){
           return $this->SendResponse("There is No Trashed Posts" , 404);
       }
       return PostResource::collection($userPost);
    }
    public function restorePost(Request $request){
        $user = Auth::user();
        $post = Post::onlyTrashed()
            ->where('user_id',$user->id)
            ->where('id',$request->id)
            ->first()->restore();
        return $this->SendResponse("Post restored successfully");

    }
    public function getPinnedPosts(){
       return Post::orderBy('pinned','desc')->get();
    }
}
