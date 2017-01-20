<?php
namespace pastuhov\logstock\tests\functional;

class SiteCest
{
    public function openViewPage(\pastuhov\logstock\tests\FunctionalTester $I)
    {
        $I->amOnPage(['site/view']);
        $I->canSee('view');
    }
}
