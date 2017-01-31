<?php

namespace pastuhov\logstock\tests\unit;

use pastuhov\logstock\filters\DynamicDataFilter;
use Yii;
use yii\codeception\TestCase;
use yii\console\Application;

class ExampleFilterTest extends TestCase
{
    public function testExampleFilterUsage()
    {
        $filter = new DynamicDataFilter();
        $filter->dynamicFields = ['expired_at', 'session_id'];
        \Yii::$app->getModule('logstock')->addFilter();

        $this->tester->assertLog(function (){
            Yii::info('Test session');
            Yii::$app
                ->getDb()
                ->createCommand(
                    "SELECT * FROM session WHERE expired_at < :EXPIRED AND session_id = :ID",
                    [
                        ':EXPIRED' => date('c'),
                        ':ID' => uniqid(),
                    ]
                )->execute();
        }, Yii::$app);
    }

    protected function _before()
    {
        $config = require('tests/app/config/console.php');
        $config['class'] = Application::class;
        $this->mockApplication($config);
    }
}
