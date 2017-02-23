<?php

namespace Santik\DoctrineMySQLGoneAwayFix;

interface MysqlGoneAwayExceptionsInterface
{
    /**
     * @param \Exception $e
     *
     * @return bool
     */
    public function isGoneAwayException(\Exception $e);
}
