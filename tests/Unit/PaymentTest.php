<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\Payment;

class PaymentTest extends TestCase
{
    /**
     * @test
     */
    public function it_updates_the_payment_status()
    {
        // Prepare assets
        $payment = new Payment();
        $possibleStatuses = [
            "APPROVED" => "payments.statuses.approved",
            "REJECTED" => "payments.statuses.rejected",
            "EXPIRED" => "payments.statuses.expired",
            "PENDING" => "payments.statuses.pending",
            "INVALID_STATUS" => "payments.statuses.pending"
        ];

        foreach ($possibleStatuses as $status => $expectedStatus ) {
            // Run test
            $payment->setStatus($status);

            // Make assertions
            $this->assertEquals($payment->status, config($expectedStatus));
        }
    }
}
