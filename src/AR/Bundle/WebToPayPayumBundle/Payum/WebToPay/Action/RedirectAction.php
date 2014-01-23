<?php
/**
 * Created by PhpStorm.
 * User: Audrius
 * Date: 14.1.22
 * Time: 22.22
 */

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action;

use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\RedirectUrlInteractiveRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RedirectAction extends PaymentAwareAction {

    function execute($request)
    {
        /** @var RedirectUrlInteractiveRequest $request */
        if (!$this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $response = new RedirectResponse($request->getUrl(), 302, array(
            'x-application-name' => 'payum-module'
        ));
        $response->send();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof RedirectUrlInteractiveRequest
            ;
    }
} 