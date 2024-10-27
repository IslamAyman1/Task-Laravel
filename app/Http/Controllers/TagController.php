<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResources;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    public function index(){
            $tags = Tag::all();
            return  TagResources::collection($tags);
    }
    public function store(Request $request){
       $validation = Validator::make([
           'name' => $request->name
       ],
       [
           'name' => 'required|string|unique:tags',
       ]);
       if($validation->fails()){
           return response()->json([$validation->errors()]);
       }
       Tag::create([
           'name' => $request->name,
           'user_id' => Auth::user()->id
       ]);
       return response()->json([
           'message' => 'Tag created successfully'
       ]);
    }
    public function update(Request $request)
    {
        $validation = Validator::make([
            'name' => $request ->name
        ],['name'=>'required|string|unique:tags']);
        if($validation->fails()) {
            return response()->json([$validation->errors()]);
        }

       $tag = Tag::where('id', $request->id)->first();
        if($tag){
            $tag->update([
                'name' => $request->name
            ]);
        }

        return response()->json([
            'message' => 'Tag does not exist'
        ]);
    }

    public function delete(Request $request){
       $tag = Tag::where('id',$request->id)->delete();
       if($tag){
           return response()->json([
               'message' => 'Tag deleted successfully'
           ]);
       }
         return response()->json([
             'message' => 'The tag does not exist'
         ]);
    }
}
