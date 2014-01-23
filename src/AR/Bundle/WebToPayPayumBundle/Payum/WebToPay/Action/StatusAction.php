<?php
/**
 * Created by PhpStorm.
 * User: Audrius
 * Date: 14.1.22
 * Time: 23.13
 */

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Action;

use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\BaseStatusRequest;
use Payum\Core\Request\BinaryMaskStatusRequest;

class StatusAction implements \Payum\Core\Action\ActionInterface
{

    CONST STATUS_REJECTED = 0x01;
    CONST STATUS_ERROR = 0x02;
    CONST STATUS_REMOTE_ERROR = 0x03;

    /**
     * {@inheritdoc}
     */
    public function execute($request)
    {
        /** @var $request \Payum\Core\Request\BinaryMaskStatusRequest */
        if (false == $this->supports($request)) {
            throw RequestNotSupportedException::createActionNotSupported($this, $request);
        }

        $model = new ArrayObject($request->getModel());

        if (empty($model['RESULT'])) {
            $request->markNew();

            return;
        }

        if (Api::RESULT_SUCCESS === (int) $model['RESULT']) {
            $request->markSuccess();

            return;
        }

        $request->markFailed();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof BinaryMaskStatusRequest ||
            $request instanceof BaseStatusRequest;
    }

} 