<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace pastuhov\logstock;

use Yii;
use yii\base\BootstrapInterface;

/**
 * The Yii Debug Module provides the debug toolbar and debugger
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Module extends \yii\base\Module implements BootstrapInterface
{

    public $logTarget;

    /**
     * @var array|Panel[] list of debug panels. The array keys are the panel IDs, and values are the corresponding
     * panel class names or configuration arrays. This will be merged with [[corePanels()]].
     * You may reconfigure a core panel via this property by using the same panel ID.
     * You may also disable a core panel by setting it to be false in this property.
     */
    public $panels = [];
    /**
     * @var string the directory storing the debugger data files. This can be specified using a path alias.
     */
    public $dataPath = '@runtime/logstock';
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
        $this->logTarget = Yii::$app->getLog()->targets['logstock'] = new LogTarget($this);

        // delay attaching event handler to the view component after it is fully configured
        $app->on(\yii\base\Application::EVENT_BEFORE_REQUEST, function () use ($app) {
            foreach (Yii::$app->getLog()->targets as $target) {
                $target->enabled = true;
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
        if (!$this->enableDebugLogs) {
            foreach (Yii::$app->getLog()->targets as $target) {
                //$target->enabled = false;
            }
        }

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

}
