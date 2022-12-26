<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;

class ShippingController extends Controller
{
    // MENGAMBIL SEMUA DAFTAR SHIPPING
    public function index(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'api_key' => 'required'
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ], 422);
        }

        // Autentikasi API menggunakan api_key
        $user = User::where('email', '=', $request->api_key)->first();
        // $user = User::where('api_key', '=', $request->api_key)->first();

        if ($user === null) {
            return response()->json([
                "success" => false,
                "message" => "unknown api_key"
            ], 422);
        }
        $shipping = Shipping::all();
        return new UserResource(true, 'List : All Shipping', $shipping);
    }
}
