<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\Book_Order;
use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    // MEMBUAT ORDER
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'api_key' => 'required',
            'payment_id' => 'required',
            'shipping_id' => 'required',
            'book_id' => 'required',
            'quantity' => 'required'
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
        $order = Order::create([
            'user_id' => $user,
            'payment_id' => $request->payment_id,
            'shipping_id' => $request->shipping_id,
            'status_id' => 2,
            'image' => NULL,
            'cost_total' => 0
        ]);
        $id = (json_decode($order))->id;
        $cost_total = 0;
        for ($i = 0; $i < count($request->book_id); $i++) {
            Book_Order::create([
                'book_id' => $request->book_id[$i],
                'order_id' => $id,
                'quantity' => $request->quantity[$i],
            ]);
            $cost_total += (Book::where('id', '=', $request->book_id[$i])->first()->value('book_cost')) * $request->quantity[$i];
        }
        $order = Order::find($id);
        $order->cost_total = $cost_total;
        $order->save();
        return new UserResource(true, 'Success : Create Order', $order);
    }
}
