<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait ApiResponser
{
    public function validateLogin($request)
    {      
        return $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:4',
        ]);
    }

    public function validateAdminLogin($request)
    {      
        return $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ]);
    }

    protected function gen_uid($l=10){
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $l);
    }

    protected function token($personalAccessToken, $message = null,  $code = 200, $user = null)
    {
        $tokenData = [
            'access_token' => $personalAccessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString(),
            'user' => ($user != null) ? $user : User::where('id', Auth::id())->first()
        ];
        return $this->success($message, $tokenData, $code);
    }

    protected function success($message = null, $data = null, $code = 200)
    {
        return response()->json([
            'success' => true,
            'status_code'=>$code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function error($message = null, $code)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }
    
    public function getPersonalAccessToken()
    {
        if (request()->remember_me === 'true')
            Passport::personalAccessTokensExpireIn(now()->addWeeks(1));

        return Auth::user()->createToken('brian');
    }    
    
}
