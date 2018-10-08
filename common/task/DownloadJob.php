<?php
namespace app\common\task;
use app\models_ext\UserExt;
use yii\base\BaseObject;
use yii\queue\JobInterface;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/9 0009
 * Time: 下午 03:56
 */
class DownloadJob extends BaseObject implements JobInterface
{
    public $id;
    public function execute($queue)
    {
        sleep(4);

        // TODO: Implement execute() method.
        $user = new UserExt();
        $user->username = $this->id . 'admin';
        $user->save(false);
        
    }
}