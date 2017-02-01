<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/base.php'),
    [
        'controllerNamespace' => 'pastuhov\logstock\tests\app\controllers',
        'components' => [
            'request' => [
                'cookieValidationKey' => 'test',
                'enableCsrfValidation' => false,
            ],
            'session' => [
                'class' => 'yii\web\DbSession',
                'sessionTable' => 'session',
            ],
        ],
    ]
);
