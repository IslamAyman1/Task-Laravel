<?php
namespace App\Traits;
use App\Models\Otp;
use App\Models\User;

trait UserTrait
{
    public function SendResponse($message , $status=200){
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }
    public function generateCode($id)
    {
       Otp::create([
           'code' => rand(100000, 999999),
           'user_id' => $id,
       ]);
       return $this->SendResponse("Success",200);
    }
}
?>
