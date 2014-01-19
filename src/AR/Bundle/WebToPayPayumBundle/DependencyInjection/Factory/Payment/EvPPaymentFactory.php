<?php

namespace AR\Bundle\WebToPayPayumBundle\DependencyInjection\Factory\Payment;

use Payum\Bundle\PayumBundle\DependencyInjection\Factory\Payment\AbstractPaymentFactory;
use Payum\Core\Exception\RuntimeException;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class EvPPaymentFactory extends AbstractPaymentFactory
{

    /**
     * {@inheritdoc}
     */
    public function create(ContainerBuilder $container, $contextName, array $config)
    {
        if (false == class_exists('WebToPay_Factory')) {
            throw new RuntimeException('Cannot find WebToPay payment factory class. Have you installed webtopay/libwebtopay package?');
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../../../Resources/config/payment'));
        $loader->load('webtopay.xml');

        return parent::create($container, $contextName, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'evp';
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        parent::addConfiguration($builder);

        /** @noinspection PhpUndefinedMethodInspection */
        $builder->children()
            ->arrayNode('api')->isRequired()->children()
            ->arrayNode('options')->isRequired()->children()
            ->scalarNode('project_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('sign_password')->isRequired()->cannotBeEmpty()->end()
            ->booleanNode('sandbox')->defaultTrue()->end()
            ->end()
            ->end()
            ->end();
    }

    /**
     * {@inheritDoc}
     */
    protected function addApis(Definition $paymentDefinition, ContainerBuilder $container, $contextName, array $config)
    {

        $factoryDefinition = new DefinitionDecorator('payum.webtopay.payment.factory.prototype');
        $factoryDefinition->replaceArgument(0, array(
            'projectId' => $config['api']['options']['project_id'],
            'password' => $config['api']['options']['sign_password'],
        ));
        $factoryDefinition->setPublic(false);
        $factoryDefinitionId = 'payum.context.' . $contextName . '.payment.factory';
        $container->setDefinition($factoryDefinitionId, $factoryDefinition);

//        $validatorDefinition = new DefinitionDecorator('payum.webtopay.payment.callback_validator.prototype');
//        $validatorDefinition->setFactoryService(new Reference($factoryDefinitionId));
//        $validatorDefinition->setPublic(false);
//        $validatorDefinitionId = 'payum.context.'.$contextName.'.payment.callback_validator';
//        $container->setDefinition($validatorDefinitionId, $validatorDefinition);
//
//        $builderDefinition = new DefinitionDecorator('payum.webtopay.payment.request_builder.prototype');
//        $builderDefinition->setFactoryService(new Reference($factoryDefinitionId));
//        $builderDefinition->setPublic(false);
//        $builderDefinitionId = 'payum.context.'.$contextName.'.payment.request_builder';
//        $container->setDefinition($builderDefinitionId, $builderDefinition);

        $paymentDefinition->addMethodCall('addApi', array(new Reference($factoryDefinitionId)));
    }

    /**
     * {@inheritDoc}
     */
    protected function addActions(Definition $paymentDefinition, ContainerBuilder $container, $contextName, array $config)
    {

        $paymentDetailsCaptureActionDefinition = new DefinitionDecorator('payum.webtopay.action.payment_details_capture.prototype');
        $paymentDetailsCaptureActionId = 'payum.context.' . $contextName . '.action.capture';

        $container->setDefinition($paymentDetailsCaptureActionId, $paymentDetailsCaptureActionDefinition);
        $paymentDefinition->addMethodCall('addAction', array(new Reference($paymentDetailsCaptureActionId)));

        $autoPayPaymentDetailsStatusActionDefinition = new DefinitionDecorator('payum.webtopay.action.autopay_payment_details_status.prototype');
        $autoPayPaymentDetailsStatusActionId = 'payum.context.' . $contextName . '.action.status';
        $container->setDefinition($autoPayPaymentDetailsStatusActionId, $autoPayPaymentDetailsStatusActionDefinition);
        $paymentDefinition->addMethodCall('addAction', array(new Reference($autoPayPaymentDetailsStatusActionId)));

        $autoPayPaymentDetailsStatusActionDefinition = new DefinitionDecorator('payum.webtopay.action.api.redirect.prototype');
        $autoPayPaymentDetailsStatusActionId = 'payum.context.' . $contextName . '.action.api.redirect';
        $container->setDefinition($autoPayPaymentDetailsStatusActionId, $autoPayPaymentDetailsStatusActionDefinition);
        $paymentDefinition->addMethodCall('addAction', array(new Reference($autoPayPaymentDetailsStatusActionId)));
    }
} 