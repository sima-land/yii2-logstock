<?php
/**
 * Application configuration shared by all applications and test types
 */
$config = [
    'id' => 'app-test',
    'basePath' => dirname(__DIR__),
    'runtimePath' => dirname(dirname(__DIR__)) . '/_output',
    'bootstrap' => ['log'],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'sqlite:' . dirname(dirname(__DIR__)) . '/_output/sqlite_test.db',
            'charset' => 'utf8',
        ],
        'log' => [
            'flushInterval' => 1, // <-- here
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'trace'],
                    'exportInterval' => 1, // <-- and here
                ],
            ],
        ],
    ],
];

if (YII_ENV_TEST) {
    // configuration adjustments for 'test' environment
    $config['bootstrap'][] = 'logstock';
    $config['modules']['logstock'] = [
        'class' => \pastuhov\logstock\Module::class,
    ];

}

return $config;

