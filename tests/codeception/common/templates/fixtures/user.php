<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$security = Yii::$app->getSecurity();
$now = new \DateTime();
return [
    'username' => $faker->userName,
    'email' => $faker->email,
    'auth_key' => $security->generateRandomString(),
    'password_hash' => $security->generatePasswordHash('password_' . $index),
    'password_reset_token' => $security->generateRandomString() . '_' . time(),
	'is_activated' => '1',
    'created_at' => $now->format('Y-m-d H:i:s'),
    'updated_at' => $now->format('Y-m-d H:i:s'),
];
