<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function create(Request $request, $item)
    {
        $sid = $request->header('session_id');
        $amount = 1;
        if(is_null($sid)){
            $sid = Uuid::generate(4)->string;
            $cart = new Cart;
            $cart->session_id = $sid;
            $cart->item = $item;
            $cart->amount = $amount;

            $cart->save();

            return response(['message'=>'Product added to cart'])->header('session-id', $sid);
        }
        else{

            $cart = new Cart;

            $result = Cart::select("*")
            ->where("item", $item)
            ->exists();


            if($result)
            {
                DB::table('cart')
                ->where('session_id',$sid)
                ->where(['item'=>$item])
                ->increment('amount',1);
            
            return response()->json(['message'=>'Product added to cart']);
            }
            else
            {
                $cart->session_id = $sid;
                $cart->item = $item;
                $cart->amount = $amount;
    
                $cart->save();
                return response()->json(['message'=>'Product added to cart']);
            }
        }
    }

    public function remove(Request $request, $item)
    {
        $result = Cart::select("*")
            ->where("item", $item)
            ->exists();
        if($result)
        {
            DB::table('cart')
            ->where('session_id',$request->header('session_id'))
            ->where('item',$item)
            ->delete();
            return response()->json(['message'=>'Product deleted successfully!']);
        }
        else{
            return response()->json(['message'=>'Product does not exist!','response code'=>400],400);
        }
    }

    public function decrease(Request $request, $item)
    {
        $result = Cart::select("*")
            ->where("item", $item)
            ->exists();

        if($result)
        {
            DB::table('cart')
            ->where('session_id',$request->header('session_id'))
            ->where('item',$item)
            ->decrement('amount',1);
            return response()->json(['message'=>'Product amount decreased successfully!']);
        }
        else{
            return response()->json(['message'=>'Product does not exist!','response code'=>400],400);
        }
    }

    public function getCart(Request $request)
    {
        $session_id = $request->header('session_id');
        $result = Cart::select("*")
            ->where("session_id", $session_id)
            ->exists();

        if($result)
        {
            $show = DB::table('cart')
            ->select('item','amount')
            ->where('session_id',$session_id)
            ->get();
            
            return response()->json($show);
        }
        else {
            return response()->json(['message'=>'Cart does not exist!','response code'=>400],400);
        }
    }
}
