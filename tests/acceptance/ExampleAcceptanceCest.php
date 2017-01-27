<?php
namespace pastuhov\logstock\tests\acceptance;

class SiteCest
{
    public function openViewPage(\pastuhov\logstock\tests\AcceptanceTester $I)
    {
        $I->amOnPage('index-test.php/?r=site/view');
        $I->canSee('view');
    }
}
