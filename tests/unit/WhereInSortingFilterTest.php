<?php

namespace pastuhov\logstock\tests\unit;

use pastuhov\logstock\filters\WhereInSortingFilter;
use Yii;

class WhereInSortingFilterTest extends UnitTestCase
{
    public function testWhereInSortingFilterUsage()
    {
        $this->tester->assertLog(function (){
            Yii::$app->getDb()->createCommand('SELECT * FROM page WHERE id IN (5, 2, 6, 1)')->execute();
        }, Yii::$app, '', [new WhereInSortingFilter()]);
    }
}
