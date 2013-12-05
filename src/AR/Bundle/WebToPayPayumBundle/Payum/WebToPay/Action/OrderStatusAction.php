<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action;

use Payum\Action\PaymentAwareAction;
use Payum\Exception\RequestNotSupportedException;
use Payum\Request\StatusRequestInterface;
use Sylius\Bundle\OrderBundle\Model\Order;

class OrderStatusAction extends PaymentAwareAction
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request StatusRequestInterface */
        if (!$this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $order = $request->getModel();
        $paymentDetails = $order->getPayment()->getDetails();

        if (empty($paymentDetails)) {
            $request->markNew();

            return;
        }

        if (isset($paymentDetails['test']) && $paymentDetails['test']) {
            $request->markSuccess();

            return;
        }

        if (isset($paymentDetails['Pending']) && true === $paymentDetails['Pending']) {
            $request->markPending();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof StatusRequestInterface &&
            $request->getModel() instanceof Order;
    }
}
