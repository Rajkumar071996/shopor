<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use Auth;
use App\Models\User;


class AuthController extends Controller
{
 

  public function register(Request $request)
  {

       $validator = Validator::make($request->all(), [
           // 'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
           // 'mobile' => 'required|numeric||unique:users',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'   => "failed",
                'statuscode'   => "TXF",
                'message'      => $validator->errors()->first(),
                'data' => $validator->errors()
            ], 200);
        }
       $otp = rand(111111, 999999); 
	   $user = User::create([
	        'email' => $request->email,
            'password' => Hash::make($request->password),
            'otp'  =>$otp,
        ]);

		if($user)
		{
            $resp['statuscode'] = 'TXN';
	       $resp['status'] = 'successOtp';
	       $resp['message'] = 'Registration successfully Otp Send on '.$request->email;
	       $resp['otp'] = $otp;
	     
		}else{
           $resp['statuscode'] = 'TXF';
           $resp['status'] = 'failed';
	       $resp['message'] = 'Registration failed';  
		}
	     return response()->json($resp); 	
    }
     

   public function verifyotp(Request $request)
    {

       $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'otp' => 'required|numeric|digits:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statuscode'   => "TXF",
                'status'   => "failed",
                'message'   => $validator->errors()->first(),
                'data'      => $validator->errors()
            ],200);
        }	

       $user = User:: where('email',$request->email)->where('otp',$request->otp)->first();

       if($user)
       {
	       	$token = $user->createToken('auth_token')->plainTextToken;
	       	 $resp['access_token'] = $token;
	       	 $resp['token_type'] = 'Bearer';
			 $resp['status'] = 'success';
	   }else{
            $resp['statuscode'] = 'TXF';
            $resp['status'] = 'failed';
	        $resp['message'] = 'Invalid credencials'; 
         
	   }
       return response()->json($resp); 		
    }



	public function login(Request $request)
	{
		if (!Auth::attempt($request->only('email', 'password'))) {
		  return response()->json([
		  	'statuscode' =>"TXN",
		  	'status' =>"failed",
		    'message' => 'Invalid login details'
		  ], 200);  
	    }

		$user = User::where('email', $request['email'])->firstOrFail();
		$token = $user->createToken('auth_token')->plainTextToken;
		return response()->json([
		           'access_token' => $token,
		           'token_type' => 'Bearer',
		]);
	}

}
