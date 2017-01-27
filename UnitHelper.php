<?php
namespace pastuhov\logstock;

use yii\base\Application;

class UnitHelper extends BaseHelper
{
    /**
     * @var \yii\console\Application|\yii\web\Application
     */
    protected $app;
    /**
     * @var \pastuhov\logstock\LogTarget $logTarget
     */
    protected $logTarget;
    /**
     * @var \pastuhov\logstock\Module;
     */
    protected $module;

    public function assertLog(callable $function, Application $app, $identifier='')
    {
        $this->module = $app->getModule('logstock');
        $this->logTarget = $this->module->getLogTarget();

        $this->logTarget->enabled = true;

        call_user_func($function);

        $this->logTarget->export();
        $this->logTarget->enabled = false;

        $fixtureFileName = $this->getFileName($identifier);

        $content = $this->module->getContent($fixtureFileName);
        if ($content === false) {
            $this->fail('Fixture has aggregated. Please restart test!');
        } else {
            $this->assertSame(
                $content[0], // Expected
                $content[1]  // Actual
            );
        }

    }

}
