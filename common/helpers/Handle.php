<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/11 0011
 * Time: 下午 02:57
 */

namespace app\common\helpers;


class Handle
{
    public static function addCustomer($event)
    {
        $id = $event->id;
        die("addCustomer, id: $id");
    }
}