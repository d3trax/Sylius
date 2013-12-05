<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Request;

use Payum\Exception\InvalidArgumentException;
use Payum\Request\BaseModelRequest;
use Sylius\Bundle\CoreBundle\Model\OrderInterface;

class RedirectRequest extends BaseModelRequest
{

    protected $callbackUrl;

    /**
     * @param OrderInterface $model
     */
    public function __construct($model, $callbackUrl)
    {
        $this->setModel($model);
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * @param OrderInterface $model
     * @throws \Payum\Exception\InvalidArgumentException
     */
    public function setModel($model)
    {
        if (!$model instanceof OrderInterface) throw new InvalidArgumentException(sprintf('Invalid model specified. Expected OrderInterface, got "%s"', is_object($model) ? get_class($model) : gettype($model)));

        $this->model = $model;
    }

    /**
     * @return OrderInterface
     */
    public function getModel()
    {
        return $this->model;
    }

    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }
} 