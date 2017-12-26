<?php

namespace app\modules\wechat\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\common\helpers\Wechat;

/**
 * Default controller for the `wechat` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        echo '';
    }

    public function actionPay(){
        echo 'this is a test';
    }

    public function actionTest(){
        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => Response::FORMAT_XML,
            'formatters' => [
                Response::FORMAT_XML => [
                    'class' => 'yii\web\XmlResponseFormatter',
                    'rootTag' => 'urlset', //根节点
                    'itemTag' => 'url', //单元
                ],
            ],
            'data' => [ //要输出的数据
                [
                    'loc' => 'http://********',
                ],
            ],
        ]);
    }

    /**
     * 验证微信
     * @return bool
     */
    public function actionAuth(){
        Wechat::Auth();
        echo ' ';
    }
}
