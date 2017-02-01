<?php

namespace pastuhov\logstock\tests\app\controllers;

use yii\web\Controller;

/**
 * Class MainController
 */
class SiteController extends Controller
{
    /**
     * @var string
     */
    public $layout = false;

    /**
     * @return string
     */
    public function actionIndex()
    {
        \Yii::trace('index');
        return 'index';
    }

    /**
     * @return string
     */
    public function actionView()
    {
        \Yii::trace('view');
        \Yii::$app->db->createCommand('SELECT * FROM page')->execute();

        return 'view';
    }

    /**
     * @return string
     */
    public function actionSession()
    {
        \Yii::$app->session->open();

        return 'session';
    }
}
