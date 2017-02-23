<?php

//To test correctly please edit your mysql settings.
//put  wait_timeout = 5 into my.cnf file

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

require_once '../vendor/autoload.php';

$config = [
    'dbname' => '**',
    'host' => '**',
    'user' => '**',
    'password' => '**',
    'dirver' => '**'
];

$manager = DriverManager::getConnection(
    array_merge(
        $config,
        [
            'wrapperClass' => 'Santik\DoctrineMySQLGoneAwayFix\Connection',
            'driverClass' => 'Santik\DoctrineMySQLGoneAwayFix\PDOMySql\Driver',
        ]
    ), new Configuration()
);

while (true) {
    $result = $manager->fetchColumn(
        "SELECT item FROM mytable where id = ?",
        ['id']
    );
    sleep(6);
    print_r($result . "\n");
}