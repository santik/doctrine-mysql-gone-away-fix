<?php

namespace Santik\DoctrineMySQLGoneAwayFix;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Prophecy\Argument;
use Santik\DoctrineMySQLGoneAwayFix\PDOMySql\Driver;

class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructWithCorrectParams()
    {
        $params = [];
        $driver = $this->prophesize(Driver::class);
        $config = $this->prophesize(Configuration::class);
        $eventManager = $this->prophesize(EventManager::class);

        new Connection($params, $driver->reveal(), $config->reveal(), $eventManager->reveal());
    }

    public function testConstructWithInCorrectParams_ShouldThrowException()
    {
        $params = [];
        $driver = $this->prophesize(\Doctrine\DBAL\Driver\Mysqli\Driver::class);
        $config = $this->prophesize(Configuration::class);
        $eventManager = $this->prophesize(EventManager::class);

        $this->expectException(\InvalidArgumentException::class);

        new Connection($params, $driver->reveal(), $config->reveal(), $eventManager->reveal());
    }

    public function testShouldRetry_DriverReturnsTrue_ShouldReturnTrue()
    {
        $params = [];
        $driver = $this->prophesize(Driver::class);
        $driver->isGoneAwayException(Argument::any())->willReturn(true);
        $config = $this->prophesize(Configuration::class);
        $eventManager = $this->prophesize(EventManager::class);

        $connection = new Connection($params, $driver->reveal(), $config->reveal(), $eventManager->reveal());

        $this->assertTrue($connection->shouldRetryAfterException(new \Exception()));
    }

    public function testShouldRetry_DriverReturnsFalse_ShouldReturnFalse()
    {
        $params = [];
        $driver = $this->prophesize(Driver::class);
        $driver->isGoneAwayException(Argument::any())->willReturn(false);
        $config = $this->prophesize(Configuration::class);
        $eventManager = $this->prophesize(EventManager::class);

        $connection = new Connection($params, $driver->reveal(), $config->reveal(), $eventManager->reveal());

        $this->assertFalse($connection->shouldRetryAfterException(new \Exception()));
    }
}
