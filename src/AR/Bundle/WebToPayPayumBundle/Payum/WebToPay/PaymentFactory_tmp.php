<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay;

use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action\CaptureOrderAction;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action\OrderStatusAction;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action\PaymentDetailsSyncAction;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action\RedirectAction;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action\StatusAction;
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

        $payment->addAction(new StatusAction);
        $payment->addAction(new CaptureOrderAction);
        $payment->addAction(new OrderStatusAction);
        $payment->addAction(new PaymentDetailsSyncAction);
        $payment->addAction(new RedirectAction);

        $payment->addAction(new ExecuteSameRequestWithModelDetailsAction);
    }

    /**
     */
    private function __construct()
    {
    }
}