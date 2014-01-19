<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay;

use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action\CaptureOrderAction;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action\OrderStatusAction;
use Payum\Core\Action\ExecuteSameRequestWithModelDetailsAction;
use Payum\Core\Extension\EndlessCycleDetectorExtension;
use Payum\Core\Payment;

abstract class PaymentFactory
{
    /**
     * @param Api $api
     *
     * @return Payment
     */

    public function create(
        /* @noinspection PhpUnusedParameterInspection */
        Api $api
    )
    {
        $payment = new Payment;

        $payment->addExtension(new EndlessCycleDetectorExtension);

        $payment->addAction(new CaptureOrderAction);
        $payment->addAction(new OrderStatusAction);

        /* @todo implement
         * $payment->addAction(new callbackCheck);
         * $payment->addAction(new StatusAction);
         */
        $payment->addAction(new ExecuteSameRequestWithModelDetailsAction);
    }

    /**
     */
    private function __construct()
    {
    }
}