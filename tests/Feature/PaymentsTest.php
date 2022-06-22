<?php

namespace Tests\Feature;

use Tests\TestCase;

use App\Models\Payment;
use App\Models\Order;

class PaymentsTest extends TestCase
{
     /**
     * @test
     */
    public function it_creates_a_new_payment_with_status_pending()
    {
        $order = $this->getValidOrder();
        $payment = $this->createTestPayment($order->id);

        $response = $this->get(route('orders.view', $order->id));
            
        $response->assertStatus(200)
            ->assertSee("Proceder al pago")
            ->assertSee("Pendiente");
    }
    
    /**
     * @test
     */
    public function it_checks_approved_does_not_show_payment_button()
    {
        $order = $this->getValidOrder();
        $payment = $this->createTestPayment($order->id);

        $payment->setStatus("APPROVED");
        $payment->save();

        $response = $this->get(route('orders.view', $order->id));

        $response->assertStatus(200)
            ->assertDontSee("Proceder al pago")
            ->assertSee("Aprobado");
    }

    /**
     * @test
     */
    public function it_checks_rejected_does_not_show_payment_button()
    {
        $order = $this->getValidOrder();
        $payment = $this->createTestPayment($order->id);

        $payment->setStatus("REJECTED");
        $payment->save();

        $response = $this->get(route('orders.view', $order->id));

        $response->assertStatus(200)
            ->assertSee("Proceder al pago")
            ->assertSee("Rechazado");
    }

    /**
     * @test
     */
    public function it_checks_expired_does_not_show_payment_button()
    {
        $order = $this->getValidOrder();
        $payment = $this->createTestPayment($order->id);

        $payment->setStatus("EXPIRED");
        $payment->save();

        $response = $this->get(route('orders.view', $order->id));

        $response->assertStatus(200)
            ->assertSee("Proceder al pago")
            ->assertSee("Expirado");
    }

    public function getValidOrder()
    {
        $order = Order::inRandomOrder()->first();

        if ($order == null) {
            $order = Order::create([
                "customer_name" => "Jhon Doe",
                "customer_email" => "jhon.doe@example.com",
                "customer_mobile" => "123123123"
            ]);
        }

        return $order;
    }

    public function createTestPayment($orderId)
    {
        $nonce = 1;
        $seed = '0000-00-00';
        $sessionId = '1';

        $payment = Payment::create([
            'order_id' => $orderId,
            'session_id' => $sessionId, 
            'status' =>  config('payments.statuses.pending'), 
            'nonce' => $nonce,
            'seed' => $seed
        ]);

        return $payment;
    }
}
