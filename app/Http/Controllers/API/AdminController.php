<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VoucherLog;
use App\Models\Deal;
use App\Models\GiftCards;
use App\Models\User;
use DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function updateUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'last_name' => 'required|string',
                'mobile' => 'required|string',
                'state' => 'required|string',
                'city' => 'required|string'
            ]);

            if ($validator->fails()) {
                return $this->error($validator->errors()->first(), 422);
            }

            $user = User::where('id', $request->user_id)->with("transStat")->with("card")->first();
            return  $user ;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile = $request->mobile;
            $user->state = $request->state;
            $user->city = $request->city;
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->address = $request->address;
            if ($user->save()) {
                //notify user
                // $details = [
                // 'subject' => "Account Update Notification",
                //  'user' => $user->first_name,
                // 'content' => "<p>You've received this email because your account details just got updated. If this was you, you can ignore this alert.</p><p>However, 
                //  if you did not authorize the pin change, Please login into your app to reset your pin or contact admin at  09041110904</p>",
                // ];
                // Mail::to($user->email)->send(new GeneralEmails($details));
                return $this->success('Profile Update successful',["user"=>$user]);
            }
            
            
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 500);
        }
    }
}
