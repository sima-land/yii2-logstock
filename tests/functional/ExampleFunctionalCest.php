<?php
namespace pastuhov\logstock\tests\functional;

class ExampleFunctionalCest
{
    public function openSiteViewRoute(\pastuhov\logstock\tests\FunctionalTester $I)
    {
        $I->assertLog(function () use ($I) {
            $I->amOnPage(['site/view']);
            $I->canSee('view');
        }, \Yii::$app);
    }
}
