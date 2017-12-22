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
}