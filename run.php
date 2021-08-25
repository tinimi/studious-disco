<?php

declare(strict_types=1);

if ($argc != 2) {
    die("Usage: {$argv[0]} filename.csv\n");
}
include __DIR__ . '/vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('config/services.yaml');

$containerBuilder->setParameter('filename', $argv[1]);

$containerBuilder->compile();

$serviceIds = $containerBuilder->findTaggedServiceIds('app.runner');
foreach ($serviceIds as $serviceId => $tags) {
    try { 
        $containerBuilder->get($serviceId)->run();
    } catch (Exception $e) {
        fwrite(STDERR, $e->getMessage(). "\n");
        exit(1);
    }
}
