<?php

declare(strict_types=1);

namespace App\Tests;

use App\DTO\CurrencyDTO;
use App\DTO\TransactionDTO;
use App\Repository\CurrencyRepository;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

abstract class AbstractMyTestCase extends TestCase
{
    protected ContainerBuilder $container;
    protected string $fileName = 'qwe';

    protected function getContainer(): ContainerBuilder
    {
        if (!isset($this->container)) {
            $this->container = new ContainerBuilder();
            $loader = new YamlFileLoader($this->container, new FileLocator(dirname(__DIR__)));
            $loader->load('config/services.yaml');
            $loader->load('config/services_test.yaml');

            $this->container->compile(true);
        }

        return $this->container;
    }

    /**
     * @param array<string> $row
     */
    protected function createTransactionFromArray(array $row): TransactionDTO
    {
        return new TransactionDTO(
            $date = new DateTimeImmutable($row[0]),
            $row[1],
            $row[2],
            $row[3],
            $row[4],
            $currency = new CurrencyDTO($row[5], 'JPY' === $row[5] ? 0 : 2)
        );
    }

    protected function getCurrencyRepository(): CurrencyRepository
    {
        return new CurrencyRepository([
            ['name' => 'EUR', 'scale' => 2],
            ['name' => 'USD', 'scale' => 2],
            ['name' => 'JPY', 'scale' => 0],
        ]);
    }
}
