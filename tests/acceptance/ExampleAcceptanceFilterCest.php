<?php
namespace pastuhov\logstock\tests\acceptance;

use pastuhov\logstock\filters\DbSessionFilter;

class ExampleAcceptanceFilterCest
{
    /**
     * @param \pastuhov\logstock\tests\AcceptanceTester $I
     */
    public function openPageWithSession(\pastuhov\logstock\tests\AcceptanceTester $I)
    {
        $I->assertLog(function () use ($I) {
            $I->amOnPage('index-test.php/?r=site/session');
            $I->canSee('session');
        }, $I, '', [new DbSessionFilter()]);
    }
}
