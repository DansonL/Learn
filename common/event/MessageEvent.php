<?php

namespace app\common\event;
use yii\base\Event;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/11 0011
 * Time: 下午 02:40
 */
class MessageEvent extends Event
{
    public $id;
    public $message;
    const EVENT_ADDCUSTOMER = 'add-customer';
}