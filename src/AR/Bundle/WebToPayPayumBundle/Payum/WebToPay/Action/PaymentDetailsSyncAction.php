<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action;


use Payum\Action\PaymentAwareAction;
use Payum\Exception\RequestNotSupportedException;
use Payum\Request\SyncRequest;

class PaymentDetailsSyncAction extends PaymentAwareAction
{

    /**
     * @param mixed $request
     *
     * @throws \Payum\Exception\RequestNotSupportedException if the action dose not support the request.
     *
     * @return void
     */
    function execute($request)
    {
        /** @var $request SyncRequest */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }
    }

    /**
     * @param mixed $request
     *
     * @return boolean
     */
    function supports($request)
    {
        return
            $request instanceof SyncRequest;
    }
}