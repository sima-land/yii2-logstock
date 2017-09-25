<?php

namespace pastuhov\logstock\tests\unit;

use pastuhov\logstock\filters\LoadSafeAttributesFilter;
use pastuhov\logstock\tests\app\models\TestModel;
use Yii;

class LoadSafeAttributesFilterTest extends UnitTestCase
{
    public function testLoadSafeAttributeWithoutFilter()
    {
        $this->tester->assertLog(function (){
            (new TestModel())->load([
                'testAttribute' => 'test',
            ], '');
        }, Yii::$app, '');
    }

    public function testLoadSafeAttributeWithFilter()
    {
        $this->tester->assertLog(function (){
            (new TestModel())->load([
                'testAttribute' => 'test',
            ], '');
        }, Yii::$app, '', [new LoadSafeAttributesFilter()]);
    }
}
