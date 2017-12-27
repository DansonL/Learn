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



        //Yii::trace(serialize($request_xml));

        $xml = '<xml><ToUserName><![CDATA[gh_c42b6c39c7d5]]></ToUserName>
                <FromUserName><![CDATA[o8JwBw5OSKLLiI3gz9TPyrJHnZH4]]></FromUserName>
                <CreateTime>1514359848</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[哈哈]]></Content>
                <MsgId>6504126022099629795</MsgId>
                </xml>';
        libxml_disable_entity_loader(true);
        //禁止xml实体解析，防止xml注入
        $request_xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $wechat = new Wechat();
        $wechat->_msgText($request_xml->FromUserName, $request_xml->ToUserName, 'hello');






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

        $wechat = new Wechat();
        $wechat->_msgText($request_xml->FromUserName, $request_xml->ToUserName, 'hello');

    }
}
