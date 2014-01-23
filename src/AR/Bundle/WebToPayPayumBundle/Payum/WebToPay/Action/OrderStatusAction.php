<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action;

use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\CheckOrderRequest;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\RedirectRequest;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\CaptureRequest;

class OrderStatusAction extends PaymentAwareAction
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var $request CaptureRequest */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (empty($model['REDIRECT_URL'])) {
            $this->payment->execute(new RedirectRequest($model));
        } else {
            $this->payment->execute(new CheckOrderRequest($model));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CaptureRequest &&
            $request->getModel() instanceof \ArrayAccess
            ;
    }
}
