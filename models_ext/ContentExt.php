<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/25 0025
 * Time: 下午 05:30
 */
namespace app\models_ext;
use app\common\event\MessageEvent;
use app\common\helpers\Handle;
use app\models\Content;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class ContentExt extends Content {

//    const EVENT_HELLO = 'hello';
//
//    public function behaviors(){
//        return [
//            [
//                'class' => AttributeBehavior::className(),
//                'attributes' => [
//                    ActiveRecord::EVENT_BEFORE_INSERT => 'token',
//                ],
//                'value' => function ($event) {
//                    return md5($this->username);
//                },
//            ],
//        ];
//    }

//    public function hellos()
//    {
//        $event = new MessageEvent();
//        $event->message = 'message';
//        $this->trigger(self::EVENT_HELLO, $event);
//    }
//
//
//
//    public function afterSave($insert, $changedAttributes)
//    {
//        //$this->trigger(self::EVENT_HELLO);
//        if (parent::afterSave($insert, $changedAttributes)){
//            $this->trigger(MessageEvent::EVENT_ADDCUSTOMER, new MessageEvent(['id'=>1]));
//        }else{
//            return;
//        }
//    }
}