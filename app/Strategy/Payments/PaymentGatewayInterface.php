<?php

namespace App\Strategy\Payments;

interface PaymentGatewayInterface
{
    public function createPayment($amount, $orderInfo, $transition_id);

    public function validateResponse($inputData);
}
