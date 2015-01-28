<?php


if (isset($_SERVER['RDS_HOSTNAME'])) {

    $container->setParameter('database_driver', 'pdo_mysql');
    $container->setParameter('database_host', $_SERVER['RDS_HOSTNAME']);
    $container->setParameter('database_port', @$_SERVER['RDS_PORT']);
    $container->setParameter('database_name', @$_SERVER['RDS_DB_NAME']);
    $container->setParameter('database_user', @$_SERVER['RDS_USERNAME']);
    $container->setParameter('database_password', @$_SERVER['RDS_PASSWORD']);
    $container->setParameter('database_path', '');

} else {

    $container->setParameter('database_driver', 'pdo_sqlite');
    $container->setParameter('database_host', '');
    $container->setParameter('database_port', '');
    $container->setParameter('database_name', '');
    $container->setParameter('database_user', '');
    $container->setParameter('database_password', '');
    $container->setParameter('database_path', 'test.sqlite');

}
