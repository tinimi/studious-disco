<?php

declare(strict_types=1);

include __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Monolog\ErrorHandler;
use Psr\Log\LoggerInterface;
use App\Runner;
use App\Exceptions\AppException;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__));
$loader->load('config/services.yaml');

$containerBuilder->compile(true);

$logger = $containerBuilder->get(LoggerInterface::class);

ErrorHandler::register($logger);
try {
    exit($containerBuilder->get(Runner::class)->main($argc, $argv));
} catch (AppException $e) {
    $logger->error($e->getMessage());
    exit(1);
}
