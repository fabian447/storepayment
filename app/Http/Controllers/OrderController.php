<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\Payment;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
    */

    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

   /**
     * Save order information.
    */
    public function store(Request $request)
    {
        $request->validate([
            "customer_name" => "required",
            "customer_email" => "required|email",
            "customer_mobile" => "required",
        ]);

        $order = Order::create($request->all() + ["status" => Config::get("orders.statuses.created")]);

        return redirect()->route('orders.view', ['id' => $order->id]);
    }

    /**
     * View order information
    */
    public function view($id)
    {
        $order = Order::find($id);
        $payment = $order->getLastPayment();
        return view('orders.view', compact('order','payment'));
    }
}
