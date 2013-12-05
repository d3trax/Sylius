<?php

namespace AR\Bundle\WebToPayPayumBundle;


use AR\Bundle\WebToPayPayumBundle\DependencyInjection\Factory\Payment\EvPPaymentFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WebToPayBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        /** @var $extension \Payum\Bundle\PayumBundle\DependencyInjection\PayumExtension */
        $extension = $container->getExtension('payum');
        $extension->addPaymentFactory(new EvPPaymentFactory());
    }
}
