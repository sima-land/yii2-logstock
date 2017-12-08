<?php
namespace pastuhov\logstock;

class BaseHelper extends \Codeception\Module
{
    /**
     * @inheritdoc
     */
    public $config = [
        'logstock-rewrite' => false, // whether to enable recreation logstock fixtures
    ];

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

    protected function getFileName($identifier='')
    {
        $caseName = str_replace('Cept.php', '', $this->test->getName());
        $search = array('/', '\\', ':');
        $replace = array('.', '.', '');
        $caseName = str_replace($search, $replace, $caseName);
        return $caseName . '.' . $identifier . '.log';
    }

}
