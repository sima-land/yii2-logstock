<?php

namespace pastuhov\logstock\tests\unit;

use Yii;
use yii\codeception\TestCase;
use yii\console\Application;

class ExampleUnitTest extends TestCase
{
    public function testExampleUnitUsage()
    {
        $this->tester->assertLog(function (){
            Yii::info('Test info message');
            Yii::$app->getDb()->createCommand('SELECT * FROM page')->execute();
        }, Yii::$app);
    }

    protected function _before()
    {
        $config = require('tests/app/config/console.php');
        $config['class'] = Application::class;
        $this->mockApplication($config);
    }
}
