<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

abstract class AbstractMyTestCase extends TestCase
{
    protected $container;
    protected $fileName = 'qwe';

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(dirname(__DIR__)));
        $loader->load('config/services.yaml');
        $loader->load('config/services_test.yaml');
        $this->container->setParameter('filename', $this->fileName);

        $this->container->compile();

        parent::__construct($name, $data, $dataName);
    }
}
