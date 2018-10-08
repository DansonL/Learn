<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/11 0011
 * Time: 上午 10:57
 */

namespace app\controllers;


use app\common\event\MessageEvent;
use app\common\helpers\Handle;
use app\models_ext\ContentExt;
use Yii;
use yii\base\Event;
use yii\web\Controller;
use yii\web\Response;

class EventController extends Controller
{
   /* public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->on(MessageEvent::EVENT_ADDCUSTOMER, [Handle::class,'addCustomer']);
            (new ContentExt)->on(ContentExt::EVENT_AFTER_UPDATE, function ($event){
                echo 'test';
            });
            return true;
        } else {
            return false;
        }
    }*/

    public function init()
    {
//        Event::on(ContentExt::className(), ContentExt::EVENT_BEFORE_UPDATE, function ($event) {
//            throw new HttpException('4012');
//        });

//        Event::on('*', '*', function ($event) {
//            // 触发任何类的任何事件
//            echo 'trigger event: ' . $event->name . '<\br>';
//        });

//        Event::on('*', '*', function ($event) {
//            // 触发任何类的任何事件
//            echo 'test';
//        });
        $this->on(MessageEvent::EVENT_ADDCUSTOMER, [Handle::class,'addCustomer']);
    }

    public function actionTest()
    {
        $model = new ContentExt();
        $model::updateAll();
        $model->on(ContentExt::EVENT_HELLO, function($event){
            echo $event->data;
        }, 'abc');
        $model->hellos();
    }
    public function actionIndex()
    {
        $content = ContentExt::findOne(['id' => 1]);

        $content->title = '123s';
        $content->update();
    }

    public function actionMessage()
    {
        //$this->trigger(MessageEvent::EVENT_ADDCUSTOMER, new MessageEvent(['id'=>1]));
        $value = time()-10;
        //echo Yii::$app->formatter->asRelativeTime();
        echo Yii::$app->formatter->asDuration(131);

    }

    public function actionDate()
    {
        var_dump(Yii::$aliases);
    }

    public function actionJson()
    {

        Yii::$app->response->format = Response::FORMAT_JSON;
        $app = ['test', 'test2'];

        return [[], $app, new \stdClass()];


    }

}

