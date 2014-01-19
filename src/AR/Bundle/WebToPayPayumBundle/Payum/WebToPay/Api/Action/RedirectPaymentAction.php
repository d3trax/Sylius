<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Action;

use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Model\RequestModel;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\RedirectRequest;
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

        $requestModel = $request->getModel();

        $billingAddress = $requestModel->getUser()->getBillingAddress();

        $model = new RequestModel();
        $model->setAmount($request->getModel()->getPayment()->getAmount());
        $model->setCurrency($request->getModel()->getPayment()->getCurrency());
        if ($billingAddress) {
            $model->setCountry($billingAddress->getCountry()->getIsoName());
        }
        $model->setOrderid($requestModel->getIdentifier());

        $model->setCallbackurl($request->getCallbackUrl());
        $model->setAccepturl($request->getCallbackUrl());
        $model->setCancelurl($request->getCallbackUrl());

        $model->setTest(in_array('ROLE_SYLIUS_ADMIN', $request->getModel()->getUser()->getRoles()));

        try {
            $url = $this->getApi()->getRequestBuilder()->buildRequestUrlFromData($model->asArray());
            $this->execute(new RedirectUrlInteractiveRequest($url));
            return;
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