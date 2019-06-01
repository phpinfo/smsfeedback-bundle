<?php
declare(strict_types=1);

namespace SmsFeedbackBundle\DependencyInjection;

use Psr\Log\LoggerInterface;
use SmsFeedback\ApiClient;
use SmsFeedback\ApiClientBuilder;
use SmsFeedback\ApiClientInterface;
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

            if (isset($config['message_template'])) {
                $messageTemplate = (string)$config['message_template'];
            }
        }

        $definition = (new Definition(ApiClient::class))
            ->addArgument($login)
            ->addArgument($password)
            ->addArgument($uri)
            ->addArgument($timeout)
            ->addArgument($logger)
            ->addArgument($messageTemplate)
            ->setFactory([self::class, 'createApiClient']);

        $container->setDefinition(ApiClientInterface::class, $definition);
    }

    public static function createApiClient(
        string $login,
        string $password,
        ?string $uri,
        ?int $timeout,
        ?LoggerInterface $logger,
        ?string $messageTemplate
    ): ApiClientInterface {
        $builder = ApiClientBuilder::create($login, $password);

        if ($uri !== null) {
            $builder->setBaseUri($uri);
        }

        if ($timeout !== null) {
            $builder->setTimeout($timeout);
        }

        if ($logger !== null) {
            $builder->setLogger($logger);
        }

        if ($messageTemplate !== null) {
            $builder->setLoggerMessageTemplate($messageTemplate);
        }

        return $builder->getApiClient();
    }
}
