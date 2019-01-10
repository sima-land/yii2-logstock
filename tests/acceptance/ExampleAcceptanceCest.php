<?php
namespace pastuhov\logstock\tests\acceptance;

class SiteCest
{
    /**
     * @param \pastuhov\logstock\tests\AcceptanceTester $I
     */
    public function openViewPage(\pastuhov\logstock\tests\AcceptanceTester $I)
    {
        $I->assertLog(function () use ($I) {
            $I->amOnPage('/index-test.php/?r=site/view&request=1');
            $I->amOnPage('/index-test.php/?r=site/view&request=2');
            $I->amOnPage('/index-test.php/?r=site/view&request=3');
            $I->canSee('view');
        }, $I);
    }
}
