<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action;

use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\SecuredCaptureRequest;
use Sylius\Bundle\CoreBundle\Model\OrderInterface;
use Sylius\Bundle\PaymentsBundle\Model\PaymentInterface;

class CaptureOrderAction extends PaymentAwareAction
{

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {

        /** @var $request SecuredCaptureRequest */
        if (!$this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        /** @var OrderInterface $order */
        $order = $request->getModel();
        $payment = $order->getPayment();

        $details = $payment->getDetails();
        $billingAddress = $order->getBillingAddress() ? $order->getBillingAddress() : $order->getUser()->getBillingAddress();
        $shippingAddress = $order->getShippingAddress() ? $order->getShippingAddress() : $order->getUser()->getShippingAddress();
        $address = ($billingAddress ? $billingAddress :
            ($shippingAddress ? $shippingAddress : null)
        );

        if (null == $address || true) {
            $order->setState(PaymentInterface::STATE_FAILED);
            $payment->setDetails(array(
                'RESULT' => StatusAction::STATUS_REJECTED
            ));
            $request->setModel($order);
            $this->payment->execute($request);
            return;
        }

        if (empty($details)) {
            $details = array(
                'orderid' => $order->getNumber(),
                'amount' => number_format($order->getTotal() / 100, 2),
                'currency' => $order->getCurrency(),
                'country' => $address->getCountry()->getIsoName(),
                'accepturl' => $request->getToken()->getTargetUrl(),
                'cancelurl' => $request->getToken()->getTargetUrl(),
                'callbackurl' => $request->getToken()->getAfterUrl(),
            );
        }

        $payment->setDetails($details);

        try {
            $request->setModel($payment);
            $this->payment->execute($request);
            $request->setModel($order);
        } catch (\Exception $e) {
            $request->setModel($order);

            throw $e;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof SecuredCaptureRequest &&
            $request->getModel() instanceof OrderInterface
            ;
    }
}
