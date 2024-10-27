<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request){

        $validated = Validator::make($request->all(),[
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ]);
        if($validated->fails()){
            return response()->json([$validated->errors()]);
        }

        $credentials = $request->only('email', 'password');
        $user = User::where('email' , $request->email)->first();
         if($user->status == 1){
             if(Auth::attempt($credentials)){
                 $user = Auth::user();
                 $token = $user->createToken('userToken')->plainTextToken;
                 $user->update([
                     'userToken' => $token,
                 ]);
                 return response()->json([
                     'user' => $user,
                     'token' => $token,
                 ]);
             }else{
                 return response()->json(['error' =>'There was an error logging you in']);

             }
         }
         return response()->json(['error' =>'please verify your email']);
    }
}
