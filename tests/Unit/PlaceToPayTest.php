<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\PaymentMethods\PlaceToPay;
use Tests\TestCase;

class PlaceToPayTest extends TestCase
{
    /**
     * @test
     */
    public function it_generates_trankey()
    {
        // Prepare assets
        $payment = new Payment([
            'nonce' => 1,
            'seed' => '2021-09-21T09:34:48-05:00'
        ]);

        $placeToPay = new PlaceToPay("","abc","");

        // Run test
        $tranKey = $placeToPay->generateTranKey($payment);

        // Make assertions
        $this->assertEquals($tranKey, "vyCbXQpSHnaSIKSNNDNVcc/cuvw=");
    }
}
