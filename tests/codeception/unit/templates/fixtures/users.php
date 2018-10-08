<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/10 0010
 * Time: 下午 05:35
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'name' => $faker->firstName,
    'phone' => $faker->phoneNumber,
    'city' => $faker->city,
    'password' => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'auth_key' => Yii::$app->getSecurity()->generateRandomString(),
    'intro' => $faker->sentence(7, true),  // generate a sentence with 7 words
];