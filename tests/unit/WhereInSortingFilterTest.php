<?php

namespace pastuhov\logstock\tests\unit;

use pastuhov\logstock\filters\WhereInSortingFilter;
use Yii;
use yii\codeception\TestCase;
use yii\console\Application;

class WhereInSortingFilterTest extends TestCase
{
    public function testWhereInSortingFilterUsage()
    {
        $this->tester->assertLog(function (){
            Yii::$app->getDb()->createCommand('SELECT * FROM page WHERE id IN (5, 2, 6, 1)')->execute();
        }, Yii::$app, '', [new WhereInSortingFilter()]);
    }

    protected function _before()
    {
        $config = require('tests/app/config/console.php');
        $config['class'] = Application::class;
        $this->mockApplication($config);
    }
}
