<?php
/**
 * Application configuration shared by all applications and test types
 */
return [
    'id' => 'app-test',
    'basePath' => dirname(__DIR__),
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite:' . dirname(__DIR__) . '/_output/sqlite_test.db',
            'charset' => 'utf8',
        ],
    ],
];
