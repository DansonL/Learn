<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/29 0029
 * Time: 下午 03:46
 */
namespace app\rbac;

use yii\rbac\Rule;

class AuthorRule extends Rule{

    public $name = 'isAuthor';

    public function execute($user, $item, $params){
        return isset($params['post']) ? $params['post']->createdBy == $user : false;
    }
}