<?php

namespace pastuhov\logstock\tests\unit;

use Yii;

class ExampleUnitTest extends UnitTestCase
{
    public function testExampleUnitUsage()
    {
        $this->tester->assertLog(function (){
            Yii::info('Test info message');
            Yii::$app->getDb()->createCommand('SELECT * FROM page')->execute();
        }, Yii::$app);
    }
}
