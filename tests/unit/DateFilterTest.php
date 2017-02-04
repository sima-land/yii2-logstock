<?php

namespace pastuhov\logstock\tests\unit;

use pastuhov\logstock\filters\DateFilter;
use Yii;

class DateFilterTest extends UnitTestCase
{
    public function testDateFilterUsage()
    {
        $this->tester->assertLog(function (){
            Yii::$app
                ->getDb()
                ->createCommand(
                    'SELECT * FROM page WHERE created_at < :DT',
                    [':DT' => date('c')]
                )->execute();
        }, Yii::$app, '', [new DateFilter()]);
    }
}
