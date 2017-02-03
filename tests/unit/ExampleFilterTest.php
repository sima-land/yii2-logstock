<?php

namespace pastuhov\logstock\tests\unit;

use pastuhov\logstock\filters\DynamicDataFilter;
use Yii;

class ExampleFilterTest extends UnitTestCase
{
    public function testExampleFilterUsage()
    {
        \Yii::$app->getModule('logstock')->addFilter(new DynamicDataFilter());

        $this->tester->assertLog(function (){
            Yii::info('Test session');
            Yii::$app
                ->getDb()
                ->createCommand(
                    "SELECT * FROM session WHERE expire < :EXPIRED",
                    [
                        ':EXPIRED' => date('c'),
                    ]
                )->execute();
        }, Yii::$app);
    }
}
