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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|numeric||unique:users',
            'password' => 'required|string|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'   => "TXF",
                'message'   => 'Validation errors',
                'data'      => $validator->errors()
            ],400);
        }
	   $user = User::create([
		    'name' => $request->name,
	        'email' => $request->email,
	        'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

		$token = $user->createToken('auth_token')->plainTextToken;
		return response()->json([
		    'access_token' => $token,
		    'token_type' => 'Bearer',
		]);
    }

	public function login(Request $request)
	{
		if (!Auth::attempt($request->only('email', 'password'))) {
		  return response()->json([
		  	'status' =>"failed",
		    'message' => 'Invalid login details'
		  ], 401);  
	    }

		$user = User::where('email', $request['email'])->firstOrFail();
		$token = $user->createToken('auth_token')->plainTextToken;
		return response()->json([
		           'access_token' => $token,
		           'token_type' => 'Bearer',
		]);
	}

}
