<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use App\Models\Book_Order;
use Illuminate\Support\Facades\Validator;

class OrderDetailController extends Controller
{
    // MENGAMBIL DETAIL ORDER
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
        $order = Order::where([
            ['id', '=', $request->order_id],
            ['user_id', '=', $user]
        ])->first();
        $book_order = Book_Order::all()->where('order_id', '=', $request->order_id);
        return new UserResource(true, 'Detail : My Order', [$order, ['message' => 'List : Ordered Item', $book_order]]);
    }
}
