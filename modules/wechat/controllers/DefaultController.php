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
        $json = '{"openid":"o8JwBw5OSKLLiI3gz9TPyrJHnZH4","nickname":"Danson","sex":1,"language":"en","city":"惠州","province":"广东","country":"中国","headimgurl":"http:\/\/wx.qlogo.cn\/mmopen\/vi_32\/Q0j4TwGTfTLRkUBx9scO4UDwB1yEYA8PhbiaibicXPKHFaahE2kicUl4zcpG7Dq2MwOicPGzJct5ELYIBKoFXicwxsZA\/0","privilege":[]}';
        $info = json_decode($json);
        var_dump($info);exit;



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
        if (Yii::$app->redis->get($code) === NULL){
            $app = Wechat::getAccessByCode($code);
            if (!$app) return false;
            $info = Wechat::getUserInfoByAccessToken($app->access_token, $app->openid);
            Yii::$app->redis->setex($code, 30, $info);
        }else{
            $info = Yii::$app->redis->get($code);
        }
        $info = json_decode($info);
        echo '名称：' . $info->nickname . '<br \>' . '性别：' . ($info->sex == 1 ? '男' : '女') . '<br \>';
        echo 'Id：' . $info->openid . '<br \>' . '语言：' . $info->language . '<br \>' ;
        echo '城市：' . $info->city . '<br \>' . '省份：' . $info->province . '<br \>' ;
        echo '国家：' . $info->country . '<br \>' . '头像地址：' . $info->headimgurl . '<br \>' ;
        echo '授权信息：' . $info->privilege;
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
