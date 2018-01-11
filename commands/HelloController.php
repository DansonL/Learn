<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        set_time_limit(0);
        while(true){
            $ch = curl_init('http://yii-dev.com/wechat/default/test-count');
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1100);
            curl_exec($ch);
            $curl_errno = curl_errno($ch);
            $curl_error = curl_error($ch);
            curl_close($ch);
            if ($curl_errno > 0) {
                echo "cURL Error ($curl_errno): $curl_error\n";
            }

//            $cmh = curl_multi_init();
//            $ch1 = curl_init();
//            curl_setopt($ch1, CURLOPT_URL, "http://yii-dev.com/wechat/default/test-count");
//            curl_multi_add_handle($cmh, $ch1);
//            curl_multi_exec($cmh, $active);
//            echo "End\n";
//            sleep(10);
        }

        //多线程下载
        $arr = [
            'baidu.com',
            'google.com',
            'tweet.com'
        ];
        $mh = curl_multi_init();
        foreach ($arr as $i=>$url){
            $conn[$i] = curl_init($url);
            curl_setopt($conn[$i],CURLOPT_RETURNTRANSFER,1);
            curl_setopt($conn[$i], CURLOPT_HEADER, 0);
            curl_setopt($conn[$i], CURLOPT_TIMEOUT, 20);
            curl_multi_add_handle($mh,$conn[$i]);
        }
        $running = null;
        //最简单方案
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);
        //获取内容
        foreach ($arr as $i => $url) {
            $res[$i]=curl_multi_getcontent($conn[$i]);
            curl_multi_remove_handle($mh, $conn[$i]);
        }
        curl_multi_close($mh);
    }
}
