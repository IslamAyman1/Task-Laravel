<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    use UserTrait;
    public function verifyCode(Request $request){
      $validator = Validator::make([
           'email' =>$request->email
       ],
       [
           'email' => 'required|email|exists:users,email',
       ]);
      if($validator->fails()){
          return response()->json([$validator->errors()]);
      }
       $userCode = User::where('email',$request->email)->first();

      if($userCode->otp->code != $request->code){
         return $this->SendResponse('Wrong Code',417);
      }
      $userCode->update([
          'status' => 1
      ]);
      $userCode->otp->delete();
      return $this->SendResponse('Email Verified Successfully',200);
    }
}
