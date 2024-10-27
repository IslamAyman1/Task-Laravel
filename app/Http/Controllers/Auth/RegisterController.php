<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use UserTrait;
    public function register(Request $request)
    {
       $rules = [
           'name' => 'required',
           'email' => 'required|email|unique:users',
           'password' => 'required|min:6',
           'phone_number' => 'required|max:11|unique:users',
       ];
       $validated = Validator::make($request->all(), $rules);
       if($validated->fails()){
           return response()->json([$validated->errors()]);
       }
       $newUser = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password),
           'phone_number' => $request->phone_number
       ]);
       if(!$newUser){
           return response()->json(['error' => 'User Registration Failed']);
       }
       $token = $newUser->createToken('userToken')->plainTextToken;
       $newUser->update([
           'userToken' => $token
       ]);

        return $this->generateCode($newUser->id);
    }
}
