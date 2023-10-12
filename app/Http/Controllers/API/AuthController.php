<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Models\User;

use App\Traits\ApiResponser;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Passport;

use Illuminate\Support\Str;

class AuthController extends Controller
{
    use ApiResponser;
   

    public function login(Request $request)
    {      
        try {
            $this->validateLogin($request);
            
            $credentials = ['username' => $request->get('username'), 'password' => $request->get('password')];
         
            if (!Auth::attempt($credentials)) {
                return $this->errorResponse(null, 'Credentials mismatch', 400);
            }
           /* $user = $request->user();           
            $tokenResult = $user->createToken('brian');
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

            $success['access_token'] =  $tokenResult->accessToken;
            $success['token_type'] = 'Bearer';
            $success['expires_at'] = Carbon::parse($tokenResult->token->expires_at)->toDateTimeString();*/

            return $this->token($this->getPersonalAccessToken(), 'Login successful');
        } catch (\Throwable $error) {
            return $this->errorResponse($error, 'Login failed', 422);
        }
    }
    
    //for student reg
    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'mobile' => 'required|string|unique:users',
                'dob' => 'required',
                'school_id' => 'required',
                'dept_id' => 'required'
            ]);

            if ($validator->fails()) {
            
                return $this->error($validator->errors()->first(), 400);
            }

            $token = random_int(1000, 9999);
            $fakeCardGenerator = random_int(10000000000, 99999999999);
       

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role' => 'user',
                'username' => strtolower($request->first_name . '.' . $request->last_name),
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'refferal_code' => $request->refferal_code,
                'state' => $request->state,
                'city' => $request->city,
                'last_active' => 1,
                'status' => 1,
                //'verify_code' => Hash::make($token),
                'role_id' => 5,
                'platform' => 'fillcart',
                'email_verified_at' => date('Y-m-d'),
                'verified' => true
            ]);

            if ($user) {
                $lastCard = Card::orderBy('created_at', 'desc')->first();
                 $card = Card::create([
                        'card' => ($lastCard != null) ? $lastCard->card + 2 :$fakeCardGenerator,
                        'customer_id' =>  $user->id,
                        'active' => 1,
                        'total_transaction' => 0
                        ]);
                        
                 $transStat = TranStats::create([
                        'customer_id' =>  $user->id,
                        'earn_point' =>  0,
                        'burn_point' => 0,
                        'total_transaction' => 0,
                        'customer_balance'=>0
                        ]);

                 $details = [
                    'title' => 'Welcome To Plenti Africa',
                    'token' => $token
                ];

                Mail::to($user->email)->send(new verifyAccountmail($details));

                Auth::attempt(['email' => $request->email, 'password' => $request->password]);

                return $this->token($this->getPersonalAccessToken(), 'User registration was sucessfull', 201);
            }
        } catch (\Throwable $th) {
         
           return $this->error($th->getMessage(), 500);
        }
    }    
    
    public function logout()
    {
        // auth()->user()->tokens()->delete();
        Auth::user()->token()->revoke();
        return $this->success('User Logged Out', 200);
    }      
    
}
