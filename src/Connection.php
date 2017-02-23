<?php

namespace Santik\DoctrineMySQLGoneAwayFix;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Cache\QueryCacheProfile;

/**
 * Class Connection.
 */
class Connection extends \Doctrine\DBAL\Connection
{
    /**
     * @var MysqlGoneAwayExceptionsInterface
     */
    protected $_driver;

    /**
     * @param array $params
     * @param Driver|MysqlGoneAwayExceptionsInterface $driver
     * @param Configuration $config
     * @param EventManager $eventManager
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        array $params,
        Driver $driver,
        Configuration $config = null,
        EventManager $eventManager = null
    )
    {
        if (!$driver instanceof MysqlGoneAwayExceptionsInterface) {
            throw new \InvalidArgumentException('Driver should implement MysqlGoneAwayExceptionsInterface');
        }

        parent::__construct($params, $driver, $config, $eventManager);
    }

    /**
     * @param string $query
     * @param array $params
     * @param array $types
     * @param QueryCacheProfile $qcp
     *
     * @return \Doctrine\DBAL\Driver\Statement The executed statement.
     *
     * @throws \Exception
     */
    public function executeQuery($query, array $params = array(), $types = array(), QueryCacheProfile $qcp = null)
    {
        $stmt = null;

        try {
            $stmt = parent::executeQuery($query, $params, $types, $qcp);
        } catch (\Exception $e) {
            if ($this->shouldRetryAfterException($e, $query)) {
                $this->reconnect();
                $stmt = parent::executeQuery($query, $params, $types, $qcp);
            } else {
                throw $e;
            }
        }

        return $stmt;
    }

    /**
     * @return \Doctrine\DBAL\Driver\Statement
     * @throws \Exception
     */
    public function query()
    {
        $stmt = null;
        $args = func_get_args();
        try {
            $stmt = $this->queryWithArgs($args);
        } catch (\Exception $e) {
            if ($this->shouldRetryAfterException($e, $args[0])) {
                $this->reconnect();
                $stmt = $this->queryWithArgs($args);
            } else {
                throw $e;
            }
        }

        return $stmt;
    }

    /**
     * @param \Exception $e
     * @param string|null $query
     *
     * @return bool
     */
    public function shouldRetryAfterException(\Exception $e, $query = null)
    {
        return $this->_driver->isGoneAwayException($e);
    }

    public function reconnect()
    {
        $this->close();
        $this->connect();
    }

    /**
     * @param $args
     * @return Driver\Statement
     */
    private function queryWithArgs($args)
    {
        switch (count($args)) {
            case 1:
                $stmt = parent::query($args[0]);
                break;
            case 2:
                $stmt = parent::query($args[0], $args[1]);
                break;
            case 3:
                $stmt = parent::query($args[0], $args[1], $args[2]);
                break;
            case 4:
                $stmt = parent::query($args[0], $args[1], $args[2], $args[3]);
                break;
            default:
                $stmt = parent::query();
                return $stmt;
        }
        return $stmt;
    }
}
