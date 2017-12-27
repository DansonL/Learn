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
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        echo '';
    }

    public function actionPay(){
        echo 'this is a test';
    }

    public function actionTest(){
        $wechat = new Wechat();
        return $wechat->xml();
    }

    /**
     * 验证微信
     * @return bool
     */
    public function actionAuth(){
        Wechat::Auth();
    }

    /**
     * 处理消息
     */
    public function actionMessage(){
        $xml_str = file_get_contents('php://input');

        if (empty($xml_str)){
            return false;
        }
        // 解析该xml字符串，利用simpleXML
        libxml_disable_entity_loader(true);
        //禁止xml实体解析，防止xml注入
        $request_xml = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
        //判断该消息的类型，通过元素MsgType
        Yii::trace(serialize($request_xml));
        return true;



    }
}
