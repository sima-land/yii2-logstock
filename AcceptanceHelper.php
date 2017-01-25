<?php
namespace pastuhov\logstock;

use yii\base\Application;
use yii\helpers\FileHelper;

class AcceptanceHelper extends \Codeception\Module
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

    protected $aggregatePath = 'tests/_data/logstock';

    public function assertLog($function, Application $app)
    {
        $this->module = $app->getModule('logstock');
        $this->logTarget = $this->module->logTarget;

        $this->logTarget->enabled = true;

        call_user_func($function);

        $this->logTarget->export();
        $this->logTarget->enabled = false;

        $fileName = $this->getFileName();
        $isFileExists = file_exists($this->aggregatePath . '/' . $fileName);
        $aggregate = $this->aggregate($this->module->dataPath);
        if (!$isFileExists) {
            file_put_contents($this->aggregatePath . '/' . $fileName, $aggregate);
            $this->fail('Fixture has aggregated. Please restart test!');
        } else {
            $this->assertSame(
                file_get_contents($this->aggregatePath . '/' . $fileName),
                $aggregate
            );
        }

    }

    protected function getFileName($identifier='')
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

    protected function aggregate($path)
    {
        $value = '';
        $manifest = $this->getManifest($path);
        foreach ($manifest as $tag=>$summary) {
            $file = $path . '/' . $tag . '.log';
            $value .= file_get_contents($file) . PHP_EOL;
            unlink($file);
        }
        unlink($path . '/index.data');

        return $value;
    }

    protected function getManifest($path, $forceReload = false)
    {
        if ($forceReload) {
            clearstatcache();
        }
        $indexFile = $path . '/index.data';

        $content = '';
        $fp = @fopen($indexFile, 'r');
        if ($fp !== false) {
            @flock($fp, LOCK_SH);
            $content = fread($fp, filesize($indexFile));
            @flock($fp, LOCK_UN);
            fclose($fp);
        }

        if ($content !== '') {
            $manifest = array_reverse(unserialize($content), true);
        } else {
            $manifest = [];
        }

        return $manifest;
    }
}
