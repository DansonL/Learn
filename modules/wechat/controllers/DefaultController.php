<?php

namespace app\modules\wechat\controllers;

use app\models_ext\SiteConfigExt;
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

        return $this->render('index');
    }

    public function actionPay(){
        $openid = 'o8JwBw5OSKLLiI3gz9TPyrJHnZH4';
        $usrInfo = Wechat::getUserInfo($openid);
        Yii::trace($usrInfo);
        echo 'done';
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
        return $wechat->xml();
    }

    /**
     * 验证微信
     * @return bool
     */
    public function actionAuth(){
        if (count($_GET) == 0){
            exit('test');
        }
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
       // $wechat->_msgText($request_xml->FromUserName, $request_xml->ToUserName, 'hello');
    }

    /**
     * 获取用户授权并且其基本资料
     */
    public function actionAuthResponse($code = 0, $state = 'fail'){
        if ($state == 'fail') return false;
        if (Yii::$app->redis->get($code) === false){
            $app = Wechat::getAccessByCode($code);
            if (!$app) return false;
            $info = Wechat::getUserInfoByAccessToken($app->access_token, $app->openid);
            Yii::trace($info);
            Yii::$app->redis->set($code, $info, 30000);
        }else{
            $info = Yii::$app->redis->get($code);
        }
        $info = json_decode($info);
        var_dump($info);
        //echo '名称：' . $info->nickname . '<\br>' . '性别' . $info->sex == 1 ? '男' : '女';
    }

    public function actionTestCount(){
        $redis = Yii::$app->redis;
        if ($redis->get('count-test') !== false){
            $redis->incr('count-test');
        }else{
            $redis->set('count-test', 0);
        }
        var_dump($redis->get('count-test'));
    }
}
