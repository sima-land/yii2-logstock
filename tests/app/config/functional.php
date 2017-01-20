<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/config.php'),
    [
        'controllerNamespace' => 'pastuhov\logstock\tests\app\controllers',
        'components' => [
            'urlManager' => [
                'showScriptName' => true,
            ],
            'request' => [
                'cookieValidationKey' => 'test',
                'enableCsrfValidation' => false,
            ],
        ],
    ]
);
