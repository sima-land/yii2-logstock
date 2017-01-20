<?php

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/config.php'),
    [
        'controllerNamespace' => 'tests\app\commands',
    ]
);
