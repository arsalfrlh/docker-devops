<?php

namespace App\Http\Controllers;

use App\Events\OrderEvent;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderApiController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $data = Order::with('user','product')->where('user_id',$user->id)->get();
        return response()->json(['message' => "Menampilkan semua order", 'success' => true, 'data' => $data], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->all(), 'success' => false], 422);
        }

        $user = $request->user();
        $product = Product::findOrFail($request->product_id);
        $order = Order::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'quantity' => $request->quantity,
            'total_payment' => $request->quantity * $product->price,
            'expired_at' => now()->addMinutes(3),
            'status' => 'pending'
        ]);
        broadcast(new OrderEvent($order));

        return response()->json(['message' => "Pembayaran berhasil dibuat", 'success' => true, 'data' => $order], 201);
    }

    public function update(Request $request, $id){
        $validator = Validator::make($request->all(),[
            'status' => 'required|in:paid,cancel'
        ]);

        if($validator->fails()){
            return response()->json(['message' => $validator->errors()->all(), 'success' => false], 422);
        }

        $user = $request->user();
        $order = Order::where('user_id', $user->id)->findOrFail($id);
        if($order->status == "pending"){
            $order->update([
                'status' => $request->status
            ]);
        }

        return response()->json(['message' => "Pembayaran berhasil di update", 'success' => true, 'data' => $order], 200);
    }
}
