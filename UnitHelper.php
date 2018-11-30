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

    /**
     * @param callable $function
     * @param Application $app
     * @param string $identifier
     * @param array $filters
     */
    public function assertLog(callable $function, Application $app, $identifier='', $filters = [])
    {
        $this->module = $app->getModule('logstock');

        if ($filters) {
            $this->module->setFilters($filters);
        }
        $this->module->rewrite = $this->config['logstock-rewrite'];

        $this->logTarget = $this->module->getLogTarget();

        $this->logTarget->enabled = true;
        $app->log->logger->flush();
        $app->log->setFlushInterval(1);

        call_user_func($function);

        $this->logTarget->export();
        $this->logTarget->enabled = false;

        $fixtureFileName = $this->getFileName($identifier);

        $content = $this->module->getContent($fixtureFileName);
        if ($content === false) {
            $this->logstockCreateFail();
        } else {
            $this->assertSame(
                $content[0], // Expected
                $content[1]  // Actual
            );
        }

    }

}
