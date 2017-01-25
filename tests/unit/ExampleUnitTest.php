<?php

namespace pastuhov\logstock\tests\unit;

use Yii;

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
        $this->mockApplication($config);
    }
}
