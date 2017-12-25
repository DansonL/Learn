<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/22 0022
 * Time: 上午 10:16
 */
namespace app\common\helpers;

use Yii;

class Wechat{
    /**
     * 微信验证
     */

    public static function Auth(){
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

    public function SetMenu($menu){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
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
}