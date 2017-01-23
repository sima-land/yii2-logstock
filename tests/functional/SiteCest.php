<?php
namespace pastuhov\logstock\tests\functional;

class SiteCest
{
    public function openViewPage(\pastuhov\logstock\tests\FunctionalTester $I)
    {
        $I->amOnPage(['site/view']);
        $I->canSee('view');

        //\Yii::$app->getModule('logstock')->logTarget->export();
        $logger = \Yii::$app->getLog()->getLogger();
        $logger->flush();
        //$logger->log('123123', Logger::LEVEL_TRACE, '123123');
        \Yii::trace('123sdfsdf');
        //$logger = \Yii::$app->getLog()->getLogger();
    }
}
