<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Action;

use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api;
use AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\UnsupportedApiException;
use Payum\Action\ActionInterface;
use Payum\ApiAwareInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

abstract class BaseApiAwareAction implements ActionInterface, ApiAwareInterface, LoggerAwareInterface
{
    protected $logger;

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @var \WebToPay_Factory
     */
    protected $api;

    /**
     * {@inheritdoc}
     */
    public function setApi($api)
    {
        if (false == $api instanceof Api) {
            throw new UnsupportedApiException('Not supported.');
        }

        $this->api = $api;
    }

    protected function getApi()
    {
        return $this->api;
    }
} 