<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace pastuhov\logstock;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Application;

/**
 * The Yii Debug Module provides the debug toolbar and debugger
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var string the directory storing the debugger data files. This can be specified using a path alias.
     */
    public $dataPath = '@runtime/logstock';

    public $fixturePath =  __DIR__ . '/tests/_data/logstock';
    /**
     * @var integer the permission to be set for newly created debugger data files.
     * This value will be used by PHP [[chmod()]] function. No umask will be applied.
     * If not set, the permission will be determined by the current environment.
     * @since 2.0.6
     */
    public $fileMode;
    /**
     * @var integer the permission to be set for newly created directories.
     * This value will be used by PHP [[chmod()]] function. No umask will be applied.
     * Defaults to 0775, meaning the directory is read-writable by owner and group,
     * but read-only for other users.
     * @since 2.0.6
     */
    public $dirMode = 0775;
    /**
     * @var integer the maximum number of debug data files to keep. If there are more files generated,
     * the oldest ones will be removed.
     */
    public $historySize = 50;
    /**
     * @var boolean whether to enable message logging for the requests about debug module actions.
     * You normally do not want to keep these logs because they may distract you from the logs about your applications.
     * You may want to enable the debug logs if you want to investigate how the debug module itself works.
     */
    public $enableDebugLogs = false;

    protected $logTarget;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->dataPath = Yii::getAlias($this->dataPath);
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $logTarget = $this->logTarget = \Yii::$app->getLog()->targets['logstock'] = new LogTarget($this);

        $app->on(Application::EVENT_BEFORE_REQUEST, function () use ($logTarget, $app) {
            $headers = $app->getRequest()->getHeaders();
            if ($headers['Logstock'] === 'true') {
                $logTarget->enabled = true;
            } elseif (isset($headers['Logstock-Get-Content'])) {
                $content = $this->getContent(base64_decode($headers['Logstock-Get-Content']));

                if ($content === false) {
                    $content[0] = $content[1] = '';
                }

                echo '<p id="expected">' . base64_encode($content[0]) . '</p>' . PHP_EOL .
                    '<p id="actual">' . base64_encode($content[1]) . '</p>'
                ;
                $app->end(0);
            }
        });


        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\web\UrlRule',
                'route' => $this->id,
                'pattern' => $this->id,
            ],
            [
                'class' => 'yii\web\UrlRule',
                'route' => $this->id . '/<controller>/<action>',
                'pattern' => $this->id . '/<controller:[\w\-]+>/<action:[\w\-]+>',
            ]
        ], false);
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->resetGlobalSettings();

        return true;
    }

    /**
     * Resets potentially incompatible global settings done in app config.
     */
    protected function resetGlobalSettings()
    {
        Yii::$app->assetManager->bundles = [];
    }


    public function getActualContent()
    {
        $value = '';
        $manifest = $this->getManifest($this->dataPath);
        foreach ($manifest as $tag=>$summary) {
            $file = $this->dataPath . '/' . $tag . '.log';
            $value .= file_get_contents($file) . PHP_EOL;
            unlink($file);
        }
        unlink($this->dataPath . '/index.data');

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

    public function getLogTarget()
    {
        return $this->logTarget;
    }

    public function getContent($fixtureFileName)
    {
        $fixtureFilePath = $this->fixturePath . '/' . $fixtureFileName;
        $actualContent = $this->getActualContent();

        if (file_exists($fixtureFilePath)) {
            $value = [
                file_get_contents($fixtureFilePath),
                $actualContent
            ];
        } else {
            file_put_contents($fixtureFilePath, $actualContent);

            $value = false;
        }

        return $value;
    }

}
