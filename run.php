<?php

declare(strict_types=1);

include __DIR__ . '/vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('config/services.yaml');

$containerBuilder->compile();

$serviceIds = $containerBuilder->findTaggedServiceIds('app.runner');
foreach ($serviceIds as $serviceId => $tags) {
    $containerBuilder->get($serviceId)->run();
}
