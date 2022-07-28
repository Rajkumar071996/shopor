<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    public function profile(Request $request)
    {
      return $request->user();
    }

    public function updateProfile(Request $request)
    {

       $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'mobile' => 'required|numeric|digits:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'   => "failed",
                'message'   => 'Validation errors',
                'data'      => $validator->errors()
            ],400);
        }

        $user = User::where('id', $request->user()->id)->update($request->all());
        $resp['status'] = 'success';
	    $resp['message'] = 'User details updated successfully'; 
	    $resp['user'] = $request->user();
        return response()->json($resp);
    }
}
