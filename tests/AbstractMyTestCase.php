<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

abstract class AbstractMyTestCase extends TestCase
{
    protected ContainerBuilder $container;
    protected string $fileName = 'qwe';

    /**
     * @param array<array> $data
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(dirname(__DIR__)));
        $loader->load('config/services.yaml');
        $loader->load('config/services_test.yaml');

        $this->container->compile(true);

        parent::__construct($name, $data, $dataName);
    }
}
