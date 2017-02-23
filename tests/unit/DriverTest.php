<?php

namespace Santik\DoctrineMySQLGoneAwayFix;


use Santik\DoctrineMySQLGoneAwayFix\PDOMySql\Driver;

class DriverTest extends \PHPUnit_Framework_TestCase
{
    public function testIsGoneAwayException_WithCorrectException_ShouldReturnTrue()
    {
        $driver = new Driver();

        $exception = new \Exception('bla bla MySQL server has gone away bla bla ');

        $this->assertTrue($driver->isGoneAwayException($exception));
    }

    public function testIsGoneAwayException_WithOtherException_ShouldReturnFalse()
    {
        $driver = new Driver();

        $exception = new \Exception('bla bla bla bla');

        $this->assertFalse($driver->isGoneAwayException($exception));
    }
}
