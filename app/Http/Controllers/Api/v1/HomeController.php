<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;

class HomeController extends Controller
{
    public function index(Request $request)
    {
       $resp['slider'] = Slider::get();
       $resp['status'] = 'success';
       $resp['message'] = 'Details fetched successfully';
      return response()->json($resp); 
    }
}
