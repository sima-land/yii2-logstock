<?php

namespace pastuhov\logstock\tests\unit;

use Yii;
use yii\codeception\TestCase;
use yii\console\Application;

class UnitTestCase extends TestCase
{
    protected function _before()
    {
        $config = require('tests/app/config/console.php');
        $config['class'] = Application::class;
        $this->mockApplication($config);
    }
}
