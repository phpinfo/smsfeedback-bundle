<?php
declare(strict_types=1);

namespace SmsFeedbackBundle\DependencyInjection;

use Psr\Log\LoggerInterface;
use SmsFeedback\ApiClient;
use SmsFeedback\ApiClientInterface;
use SmsFeedback\Factory\ApiClientFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

class SmsFeedbackExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config        = $this->processConfiguration($configuration, $configs);

        $login    = (string)($config['login'] ?? '');
        $password = (string)($config['password'] ?? '');

        $uri = null;
        if (isset($config['uri'])) {
            $uri = (string)$config['uri'];
        }

        $timeout = null;
        if (isset($config['timeout'])) {
            $timeout = (int)$config['timeout'];
        }

        $logger          = null;
        $messageTemplate = null;
        if (!empty($config['logger']['enabled'])) {
            $loggerService = $config['logger']['service'] ?? LoggerInterface::class;
            $logger        = new Reference($loggerService);
        }

        $definition = (new Definition(ApiClient::class))
            ->addArgument($login)
            ->addArgument($password)
            ->addArgument($uri)
            ->addArgument($timeout)
            ->addArgument($logger)
            ->setFactory([ApiClientFactory::class, 'createApiClient']);

        $container->setDefinition(ApiClientInterface::class, $definition);
    }
}
