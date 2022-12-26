<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;

class PaymentController extends Controller
{
    // MENGAMBIL SEMUA DAFTAR PAYMENT
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
        $payment = Payment::all();
        return new UserResource(true, 'List : All Payment', $payment);
    }
}
