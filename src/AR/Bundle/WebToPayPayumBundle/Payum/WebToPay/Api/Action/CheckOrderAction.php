<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Action;


use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Model\PaymentStatusModel;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\CheckOrderRequest;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\UpdatePaymentStatus;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\LogicException;
use Payum\Core\Exception\RequestNotSupportedException;

class CheckOrderAction extends BaseApiAwareAction
{

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof CheckOrderRequest &&
            $request->getModel() instanceof \ArrayAccess;
    }

    /**
     * @param mixed $request
     *
     * @throws \Payum\Core\Exception\RequestNotSupportedException if the action dose not support the request.
     * @throws \Payum\Core\Exception\LogicException
     * @return void
     */
    function execute($request)
    {
        /** @var $request CheckOrderRequest */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = ArrayObject::ensureArrayObject($request->getModel());

        try {
            $callbackValidator = $this->api->getCallbackValidator();
        } catch (\WebToPay_Exception_Configuration $e) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        try {
            $model->replace(new PaymentStatusModel($callbackValidator->validateAndParseData($model)));
        } catch (\WebToPayException $e) {
            throw new LogicException('Validation fails');
        }

        $this->execute(new UpdatePaymentStatus($model));
    }
}