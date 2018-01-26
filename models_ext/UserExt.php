<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/25 0025
 * Time: 下午 05:09
 */
namespace app\models_ext;

use app\models\User;
use Yii;
use yii\web\IdentityInterface;


class UserExt extends User implements IdentityInterface{
    public static function getModel(){
        return self::$model;
    }

    public static function findIdentity($id){

        return self::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return self::findOne(['access_token' => $token]);
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        if ($authKey == $this->auth_key) return true;
        return false;
    }

    public static function findByUsername($name){
        return self::findOne(['username' => $name]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}