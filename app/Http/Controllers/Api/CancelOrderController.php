<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use App\Models\Book_Order;
use Illuminate\Support\Facades\Validator;

class CancelOrderController extends Controller
{
    // MENGAMBIL SEMUA DAFTAR BUKU & DETAIL
    public function index(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'api_key' => 'required',
            'order_id' => 'required'
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

        $user = $user->value('id');
        // $order = Order::where([
        //     ['id', '=', $request->order_id],
        //     ['user_id', '=', $user]
        // ])->delete();

        $order = Order::where([
            ['id', '=', $request->order_id],
            ['user_id', '=', $user]
        ])->first();
        if ($order->value('status_id') === 2) {
            $order->delete();
            return new UserResource(true, 'Success : Cancel Order', $order);
        } else {
            return response()->json([
                "success" => false,
                "message" => "order is processed and can't be cancelled"
            ], 422);
        }
    }
}
