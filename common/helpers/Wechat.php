<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22 0022
 * Time: 上午 10:16
 */
namespace app\common\helpers;

use app\models_ext\SiteConfigExt;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\web\Response;

class Wechat{

    //private $access_token;

    //private static $appid;

    //private static $secret;

    public function __construct()
    {

    }

    /**
     * 微信验证
     */
    public static function auth(){
        $request = Yii::$app->request->get();
        $timestamp = $request['timestamp'];
        $nonce = $request['nonce'];
        $signature = $request['signature'];
        $tmpArr = [Yii::$app->params['wechat_token'], $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $sign = sha1($tmpStr);
        if ($sign == $signature){
            echo $request['echostr'];
            return true;
        }
        return false;
    }

    /**
     * 获取微信的AccessToken
     * @return mixed
     */
    public static function getAccessToken(){
        $model = new SiteConfigExt();
        $model = $model::findOne(['config_name' => 'wechat']);
        //属性映射
        $config = $model->confVal;
        if (!isset($config->access_token) || (isset($config->access_token) && (time() - $model->updated) >= 7200)){
            $accessCode = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $config->appId .'&secret=' . $config->appsecret);
            $accessCode = json_decode($accessCode);
            //保存accessCode
            $config->access_token = $accessCode->access_token;
            $model->confVal = $config;
            $model->save();
        }
        return $config->access_token;
    }

    /**
     * 发送公众号菜单
     * @param $menu
     * @return mixed|string
     * $menu结构为 {
                    "button":[
                    {
                    "type":"click",
                    "name":"今日歌曲",
                    "key":"V1001_TODAY_MUSIC"
                    },
                    {
                    "name":"菜单",
                    "sub_button":[
                    {
                    "type":"view",
                    "name":"搜索",
                    "url":"http://www.soso.com/"
                    },
                    {
                    "type":"miniprogram",
                    "name":"wxa",
                    "url":"http://mp.weixin.qq.com",
                    "appid":"wx286b93c14bbf93aa",
                    "pagepath":"pages/lunar/index"
                    },
                    {
                    "type":"click",
                    "name":"赞一下我们",
                    "key":"V1001_GOOD"
                    }]
                    }]
                   }
     *
     */
    public static function setMenu($menu){
        $menu = json_encode($menu, JSON_UNESCAPED_UNICODE);
        $access_token = Wechat::getAccessToken();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=". $access_token);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $menu);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $tmpInfo;

    }

    public function xml(){
        return \Yii::createObject([
            'class' => 'yii\web\Response',
            'format' => Response::FORMAT_XML,
            'formatters' => [
                Response::FORMAT_XML => [
                    'class' => 'yii\web\XmlResponseFormatter',
                    'rootTag' => false, //根节点
                    'itemTag' => 'url', //单元
                    'useObjectTags' => false
                ],
            ],
            'data' => [ //要输出的数据
                [
                    'loc' => 'http://********',
                    'loc' => 'http://********',
                ],
            ],
        ]);
    }
}