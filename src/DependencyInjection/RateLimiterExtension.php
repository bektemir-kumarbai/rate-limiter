<?php

namespace RateLimiter\RateLimiter\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class RateLimiterExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Установить параметры в контейнер
        $container->setParameter('rate_limiter.max_attempts', $config['max_attempts']);
        $container->setParameter('rate_limiter.interval', $config['interval']);
        $container->setParameter('rate_limiter.block_duration', $config['block_duration']);

        // Загрузка сервиса
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }
}