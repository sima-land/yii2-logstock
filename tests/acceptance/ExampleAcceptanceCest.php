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
            $I->amOnPage('index-test.php/?r=site/view');
            $I->canSee('view');
        }, $I);
    }
}
