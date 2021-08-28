<?php

declare(strict_types=1);

namespace App\Tests;

use App\Runner;
use Psr\Log\LoggerInterface;

class RunnerTest extends AbstractMyTestCase
{
    protected string $fileName = '/tmp/test.csv';

    public function testRunnerWithoutSort(): void
    {
        $data = <<<QWE
        2014-12-31,4,private,withdraw,1200.00,EUR
        2015-01-01,4,private,withdraw,1000.00,EUR
        2016-01-05,4,private,withdraw,1000.00,EUR
        2016-01-05,1,private,deposit,200.00,EUR
        2016-01-06,2,business,withdraw,300.00,EUR
        2016-01-06,1,private,withdraw,30000,JPY
        2016-01-07,1,private,withdraw,1000.00,EUR
        2016-01-07,1,private,withdraw,100.00,USD
        2016-01-10,1,private,withdraw,100.00,EUR
        2016-01-10,2,business,deposit,10000.00,EUR
        2016-01-10,3,private,withdraw,1000.00,EUR
        2016-02-15,1,private,withdraw,300.00,EUR
        2016-02-19,5,private,withdraw,3000000,JPY
        QWE;
        $this->expectOutputString(<<<ASD
        0.60
        3.00
        0.00
        0.06
        1.50
        0
        0.69
        0.30
        0.30
        3.00
        0.00
        0.00
        8611
        
        ASD);
        file_put_contents($this->fileName, $data);

        /**
         * @var Runner;
         */
        $runner = $this->container->get(Runner::class);
        $this->assertNotNull($runner);
        $runner->runWithoutSort($this->fileName);
    }

    public function testRunnerWithSort(): void
    {
        $data = <<<QWE
        2016-02-19,5,private,withdraw,3000000,JPY
        2014-12-31,4,private,withdraw,1200.00,EUR
        2015-01-01,4,private,withdraw,1000.00,EUR
        2016-01-05,4,private,withdraw,1000.00,EUR
        2016-01-05,1,private,deposit,200.00,EUR
        2016-01-06,2,business,withdraw,300.00,EUR
        2016-01-07,1,private,withdraw,1000.00,EUR
        2016-01-06,1,private,withdraw,30000,JPY
        2016-01-07,1,private,withdraw,100.00,USD
        2016-01-10,1,private,withdraw,100.00,EUR
        2016-01-10,2,business,deposit,10000.00,EUR
        2016-01-10,3,private,withdraw,1000.00,EUR
        2016-02-15,1,private,withdraw,300.00,EUR
        QWE;
        $this->expectOutputString(<<<ASD
        8611
        0.60
        3.00
        0.00
        0.06
        1.50
        0.69
        0
        0.30
        0.30
        3.00
        0.00
        0.00
        
        ASD);
        file_put_contents($this->fileName, $data);

        /**
         * @var Runner;
         */
        $runner = $this->container->get(Runner::class);
        $this->assertNotNull($runner);
        $runner->runWithSort($this->fileName);
    }

    public function testRun1(): void
    {
        $loggerMock = $this->createStub(LoggerInterface::class);

        $stub = $this->getMockBuilder(Runner::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['runWithSort', 'runWithoutSort'])
            ->getMock();

        $stub->expects($this->once())
            ->method('runWithSort');
        $stub->expects($this->exactly(0))
            ->method('runWithoutSort');

        $fileName = $this->fileName;
        $propertyClosure = function () use ($fileName, $loggerMock) {
            $this->logger = $loggerMock; // @phpstan-ignore-line
            $this->sort = true; // @phpstan-ignore-line
            $this->run($fileName); // @phpstan-ignore-line
        };

        $doPropertyClosure = $propertyClosure->bindTo($stub, get_class($stub));
        $doPropertyClosure();
    }

    public function testRun2(): void
    {
        $loggerMock = $this->createStub(LoggerInterface::class);

        $stub = $this->getMockBuilder(Runner::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['runWithSort', 'runWithoutSort'])
            ->getMock();

        $stub->expects($this->once())
            ->method('runWithoutSort');
        $stub->expects($this->exactly(0))
            ->method('runWithSort');

        $fileName = $this->fileName;
        $propertyClosure = function () use ($fileName, $loggerMock) {
            $this->logger = $loggerMock; // @phpstan-ignore-line
            $this->sort = false; // @phpstan-ignore-line
            $this->run($fileName); // @phpstan-ignore-line
        };

        $doPropertyClosure = $propertyClosure->bindTo($stub, get_class($stub));
        $doPropertyClosure();
    }
}
