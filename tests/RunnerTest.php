<?php

declare(strict_types=1);

namespace App\Tests;

use App\Exceptions\FileNotFoundException;
use App\Runner;
use org\bovigo\vfs\vfsStream;
use Psr\Log\LoggerInterface;

class RunnerTest extends AbstractMyTestCase
{
    public function testRunnerWithoutSort(): void
    {
        $structure = [
            'input.csv' => <<<QWE
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
                QWE
        ];

        $root = vfsStream::setup('root', null, $structure);

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

        /**
         * @var Runner;
         */
        $runner = $this->getContainer()->get(Runner::class);
        $this->assertNotNull($runner);
        $runner->runWithoutSort($root->url().'/input.csv');
    }

    public function testRunnerWithSort(): void
    {
        $structure = [
            'input.csv' => <<<QWE
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
                QWE
        ];

        $root = vfsStream::setup('root', null, $structure);

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

        /**
         * @var Runner;
         */
        $runner = $this->getContainer()->get(Runner::class);
        $this->assertNotNull($runner);
        $runner->runWithSort($root->url().'/input.csv');
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

        $propertyClosure = function () use ($loggerMock) {
            $this->logger = $loggerMock; // @phpstan-ignore-line
            $this->sort = true; // @phpstan-ignore-line
            $this->run('qwe'); // @phpstan-ignore-line
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

        $propertyClosure = function () use ($loggerMock) {
            $this->logger = $loggerMock; // @phpstan-ignore-line
            $this->sort = false; // @phpstan-ignore-line
            $this->run('qwe'); // @phpstan-ignore-line
        };

        $doPropertyClosure = $propertyClosure->bindTo($stub, get_class($stub));
        $doPropertyClosure();
    }

    public function testMainCallRun(): void
    {
        $stub = $this->getMockBuilder(Runner::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['run'])
            ->getMock();

        $stub->expects($this->once())
            ->method('run')
            ->with('filename.csv')
            ->willReturn(0);

        $this->assertEquals(0, $stub->main(2, ['qwe', 'filename.csv']));
    }

    public function testMainCallLogger(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())
            ->method('error');

        $stub = $this->getMockBuilder(Runner::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['run'])
            ->getMock();

        $stub->expects($this->exactly(0))
            ->method('run');

        $that = $this;
        $propertyClosure = function () use ($that, $loggerMock) {
            $this->logger = $loggerMock; // @phpstan-ignore-line
            $this->sort = false; // @phpstan-ignore-line
            $that->assertEquals(1, $this->main(1, ['qwe'])); // @phpstan-ignore-line
        };
        $doPropertyClosure = $propertyClosure->bindTo($stub, get_class($stub));
        $doPropertyClosure();
    }

    public function testRunException(): void
    {
        $loggerMock = $this->createMock(LoggerInterface::class);
        $loggerMock->expects($this->once())
            ->method('error');

        $stub = $this->getMockBuilder(Runner::class)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->onlyMethods(['runWithSort', 'runWithoutSort'])
            ->getMock();

        $stub->expects($this->once())
            ->method('runWithSort')
            ->willThrowException(new FileNotFoundException());

        $that = $this;
        $propertyClosure = function () use ($that, $loggerMock) {
            $this->logger = $loggerMock; // @phpstan-ignore-line
            $this->sort = true; // @phpstan-ignore-line
            $that->assertEquals(1, $this->run('qwe')); // @phpstan-ignore-line
        };
        $doPropertyClosure = $propertyClosure->bindTo($stub, get_class($stub));
        $doPropertyClosure();
    }
}
