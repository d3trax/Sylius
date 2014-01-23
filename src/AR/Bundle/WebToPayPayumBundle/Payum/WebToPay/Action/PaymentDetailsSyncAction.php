<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action;

use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\UpdatePaymentStatus;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

class PaymentDetailsSyncAction extends PaymentAwareAction
{

    /**
     * @param mixed $request
     *
     * @throws \Payum\Core\Exception\RequestNotSupportedException if the action dose not support the request.
     *
     * @return void
     */
    function execute($request)
    {
        /** @var $request UpdatePaymentStatus */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $model->replace($model);
    }

    /**
     * @param mixed $request
     *
     * @return boolean
     */
    function supports($request)
    {
        return
            $request instanceof UpdatePaymentStatus &&
            $request->getModel() instanceof \ArrayAccess
            ;
    }
}