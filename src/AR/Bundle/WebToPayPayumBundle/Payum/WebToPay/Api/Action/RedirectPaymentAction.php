<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Action;

use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Model\RequestModel;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\RedirectRequest;
use Payum\Exception\LogicException;
use Payum\Exception\RequestNotSupportedException;
use Payum\Request\RedirectUrlInteractiveRequest;

class RedirectPaymentAction extends BaseApiAwareAction
{

    protected $projectId;
    protected $signPassword;

    function setProjectId($id)
    {
        $this->projectId = $id;
    }

    function setSignPassword($password)
    {
        $this->projectId = $password;
    }

    /**
     * @param mixed $request
     *
     * @throws \Payum\Exception\RequestNotSupportedException if the action dose not support the request.
     * @throws \Payum\Exception\LogicException
     * @return void
     */
    function execute($request)
    {
        /** @var RedirectRequest $request */
        if (!$this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $requestModel = $request->getModel();

        $model = new RequestModel();
        $model->setAmount($request->getModel()->getPayment()->getAmount());
        $model->setCurrency($request->getModel()->getPayment()->getCurrency());
        $model->setCountry($requestModel->getUser()->getBillingAddress()->getCountry()->getIsoName());
        $model->setProjectid($this->projectId);
        $model->setSignPassword($this->signPassword);
        $model->setOrderid($requestModel->getIdentifier());

        $model->setCallbackurl($request->getCallbackUrl());
        $model->setAccepturl($request->getCallbackUrl());
        $model->setCancelurl($request->getCallbackUrl());

        $model->setTest($request->getModel()->getUser()->getRoles() === 'ROLE_SYLIUS_ADMIN');

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