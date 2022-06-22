<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DateTime;

use App\Models\Order;
use App\Models\Payment;
use App\PaymentMethods\PlaceToPay;

class PaymentController extends Controller
{
    /**
     * Create a new payment
    */
    public function pay($order_id)
    {
        $order = Order::findOrFail($order_id);

        $payment = Payment::where([
            "order_id" => $order->id,
            "status" => config('payments.statuses.pending')
        ])->first();

        if($payment != null){
            return redirect()->away($payment->process_url);
        }

        $placeToPay = new PlaceToPay(
            env('PLACE_TO_PAY_LOGIN'),
            env('PLACE_TO_PAY_SECRET_KEY'),
            env('PLACE_TO_PAY_BASE_URL')
        );

        $payment = $this->createPayment($order);
        
        $order->status = config('orders.statuses.created');
        $order->save();

        // TODO: validate login errors
        $data = $placeToPay->generatePaymentUrl($payment);
     
        if ($data->status->status != "OK"){
            return redirect()->back();
        } 

        $payment->request_id = $data->requestId;
        $payment->process_url = $data->processUrl;
        $payment->save();

        return redirect()->away($payment->process_url); 
    }

    public function createPayment($order)
    {
        $timestamp = new DateTime();
        $nonce = rand(1, 9999999999);
        $seed = $timestamp->format(DateTime::ISO8601);
        $session_id = $order->id.'-'.$timestamp->getTimestamp();

        $payment = Payment::create([
            'order_id' => $order->id,
            'session_id' => $session_id, 
            'status' =>  config('payments.statuses.pending'),
            'nonce' => $nonce,
            'seed' => $seed
        ]);

        return $payment;
    }

    public function processPayment($id)
    {
        $payment = Payment::findOrFail($id);
        $order = Order::findOrFail($payment->order_id);

        $placeToPay = new PlaceToPay(
            env('PLACE_TO_PAY_LOGIN'),
            env('PLACE_TO_PAY_SECRET_KEY'),
            env('PLACE_TO_PAY_BASE_URL')
        );

        // TODO: validate login errors
        $data = $placeToPay->getPaymentInformation($payment);

        if ($data->status->status == config('payments.statuses.failed'))
        {
            return redirect()->route('orders.index')->with('status', 'Ocurrió un error inesperado.');
        }

        if ($data->payment == null || count($data->payment) == 0) {
            // order still pending
            return redirect()->route('orders.view', ['id' => $order->id]); 
        }

        if ($data->payment['0']->reference != $payment->session_id) {
            return redirect()->route('orders.index')->with('status', 'Ocurrió un error al procesar el pago.');
        }

        $payment->setStatus($data->status->status);
        $payment->save();

        if ($data->status->status != config('payments.statuses.approved')) {

            if ($data->status->status != config('payments.statuses.pedning')){
                $order->status = config("orders.statuses.rejected");
                $order->save();
            } 

            return redirect()->route('orders.view', ['id' => $order->id])->with('status', 'El pago se encuentra en estado: '.$data->status->status);
        }

        $order->status = config("orders.statuses.payed");
        $order->save();
        
        return redirect()->route('orders.view', ['id' => $order->id]);  
    }

}
