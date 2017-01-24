<?php
namespace pastuhov\logstock;

use Codeception\Util\Debug;

class UnitHelper extends \Codeception\Module
{
    public function assertLog($function)
    {
        /** @var \pastuhov\logstock\LogTarget $logTarget */
        $logTarget = \Yii::$app->getModule('logstock')->logTarget;
        $logTarget->enabled = true;

        call_user_func($function);

        $logTarget->export();
        $logTarget->enabled = false;

        Debug::debug($this->_getFileName());

        $this->assertSame('123', '123');
    }

    protected function _getFileName($identifier='')
    {
        $caseName = str_replace('Cept.php', '', $this->test->getName());
        $search = array('/', '\\', ':');
        $replace = array('.', '.', '');
        $caseName = str_replace($search, $replace, $caseName);
        return $caseName . '.' . $identifier . '.log';
    }

    /**
     * Event hook before a test starts
     *
     * @param \Codeception\TestCase $test
     * @throws \Exception
     */
    public function _before(\Codeception\TestCase $test)
    {
        $this->test = $test;
    }
}
