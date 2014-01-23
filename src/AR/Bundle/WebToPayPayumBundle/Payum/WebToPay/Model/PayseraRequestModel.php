<?php

namespace AR\Bundle\WebToPayPayumBundle\Payum\WebToPay\Api\Model;


class PayseraRequestModel
{
    /**
     * @param mixed $accepturl
     */
    public function setAccepturl($accepturl)
    {
        $this->accepturl = $accepturl;
    }

    /**
     * @return mixed
     */
    public function getAccepturl()
    {
        return $this->accepturl;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $callbackurl
     */
    public function setCallbackurl($callbackurl)
    {
        $this->callbackurl = $callbackurl;
    }

    /**
     * @return mixed
     */
    public function getCallbackurl()
    {
        return $this->callbackurl;
    }

    /**
     * @param mixed $cancelurl
     */
    public function setCancelurl($cancelurl)
    {
        $this->cancelurl = $cancelurl;
    }

    /**
     * @return mixed
     */
    public function getCancelurl()
    {
        return $this->cancelurl;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $orderid
     */
    public function setOrderid($orderid)
    {
        $this->orderid = $orderid;
    }

    /**
     * @return mixed
     */
    public function getOrderid()
    {
        return $this->orderid;
    }

    /**
     * @param mixed $projectid
     */
    public function setProjectid($projectid)
    {
        $this->projectid = $projectid;
    }

    /**
     * @return mixed
     */
    public function getProjectid()
    {
        return $this->projectid;
    }

    /**
     * @param mixed $sign_password
     */
    public function setSignPassword($sign_password)
    {
        $this->sign_password = $sign_password;
    }

    /**
     * @return mixed
     */
    public function getSignPassword()
    {
        return $this->sign_password;
    }

    /**
     * @param mixed $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return mixed
     */
    public function getTest()
    {
        return $this->test;
    }

    protected $projectid;
    protected $sign_password;
    protected $orderid;
    protected $amount;
    protected $currency;
    protected $country;
    protected $accepturl;
    protected $cancelurl;
    protected $callbackurl;
    protected $test;

    public function asArray()
    {
        return array(
            'projectid' => $this->getProjectid(),
            'sign_password' => $this->getSignPassword(),
            'orderid' => $this->getOrderid(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'country' => $this->getCountry(),
            'accepturl' => $this->getAccepturl(),
            'cancelurl' => $this->getCancelurl(),
            'callbackurl' => $this->getCallbackurl(),
            'test' => $this->getTest(),
        );
    }
}