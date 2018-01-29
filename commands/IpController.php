<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/29 0029
 * Time: 上午 09:37
 */

namespace app\commands;

use yii\httpclient\Client;
use yii\console\Controller;

class IpController extends  Controller{
    public function actionIndex(){
        $url = 'http://wechat.ldc0752.top/site/test';
        $proxy = '36.66.213.167:1080';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        //curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
        $content = curl_exec($ch);
        if(curl_error($ch))
        {
            echo 'error:' . curl_error($ch) . "\n";
        }
        curl_close($ch);
        echo $content;
    }
    public function actionTest(){
        $client = new Client(['transport' => 'yii\httpclient\CurlTransport']);
        $url = 'http://wechat.ldc0752.top/site/test';
        //初始化数据
        $rand_ip = long2ip(mt_rand(1947274754,1947317247));
        $rand_fip = long2ip(mt_rand(2004634437,2004664319));
        $cli = $client->createRequest()
            ->setUrl($url)
            ->setHeaders(['CLIENT_IP'=>$rand_ip, 'TRUE_IP'=>$rand_fip, 'REAL_IP'=>$rand_ip, 'X_FORWARDED_FOR'=>$rand_ip.','.$rand_fip])
            ->setOptions([CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.37']);
        try{
            $res = $cli->send();
        }catch(\Exception $e){
            var_dump($e->getMessage());
            return false;
        }
        //失败报错
        if($res->isOk == false){
            $this->stderr('请求失败');
        }
        var_dump($res->getContent());exit;
        return $res->getContent();
    }
}