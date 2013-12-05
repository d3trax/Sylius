<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action;

//use Payum\Request\CaptureRequest;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request\RedirectRequest;
use Payum\Action\PaymentAwareAction;
use Payum\Exception\RequestNotSupportedException;
use Payum\Request\SecuredCaptureRequest;
use Sylius\Bundle\CoreBundle\Model\OrderInterface;
use Sylius\Bundle\OrderBundle\Model\Order;

class CaptureOrderAction extends PaymentAwareAction
{

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {

//        /** @var $request CaptureRequest */
        /** @var $request SecuredCaptureRequest */
        if (!$this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        /** @var OrderInterface $order */
        $order = $request->getModel();

        $paymentDetails = $order->getPayment()->getDetails();

        if (empty($paymentDetails)) {
            $this->payment->execute(new RedirectRequest($order, $request->getToken()->getTargetUrl()));

            $order->getPayment()->setDetails(array(
                'status' => true,
            ));
            return;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof SecuredCaptureRequest &&
            $request->getModel() instanceof Order;
    }
}
