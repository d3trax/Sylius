<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Action;

use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\RedirectRequest;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\UpdatePaymentStatus;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\RedirectUrlInteractiveRequest;

class RedirectPaymentAction extends BaseApiAwareAction
{

    /**
     * @param mixed $request
     *
     * @throws \Payum\Core\Exception\RequestNotSupportedException if the action dose not support the request.
     * @throws \Payum\Core\Exception\LogicException
     * @return void
     */
    function execute($request)
    {
        /** @var RedirectRequest $request */
        if (!$this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = $request->getModel();

        try {
            $model['REDIRECT_URL'] = $this->getApi()->getRequestBuilder()->buildRequestUrlFromData($model);;
            $this->execute(new UpdatePaymentStatus($model));
            throw new RedirectUrlInteractiveRequest($model['REDIRECT_URL']);
        } catch (\WebToPay_Exception_Validation $e) {
            throw new LogicException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param mixed $request
     *
     * @return boolean
     */
    function supports($request)
    {
        return $request instanceof RedirectRequest;
    }
}