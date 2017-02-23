<?php

namespace Santik\DoctrineMySQLGoneAwayFix\PDOMySql;
use Santik\DoctrineMySQLGoneAwayFix\MysqlGoneAwayExceptionsInterface;

/**
 * Class Driver.
 */
class Driver extends \Doctrine\DBAL\Driver\PDOMySql\Driver implements MysqlGoneAwayExceptionsInterface
{
    /**
     * @var array
     */
    protected $mysqlGoneAwayException = 'MySQL server has gone away';

    /**
     * @param \Exception $exception
     *
     * @return bool
     */
    public function isGoneAwayException(\Exception $exception)
    {
        return stripos($exception->getMessage(), $this->mysqlGoneAwayException) !== false;
    }
}
