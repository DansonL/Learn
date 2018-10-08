<?php

namespace app\modules\admin\controllers;

use app\models_ext\SiteConfigExt;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $config = new SiteConfigExt();
        $config->config_name = 'test';
        $config->config_value = ['test'];
        var_dump($config->save(false));
    }

    public function actionTest()
    {
        var_dump(Yii::$aliases);exit;
    }
}
