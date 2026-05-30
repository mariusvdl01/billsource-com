<?php

namespace tests\codeception\frontend\unit\models;

use Yii;
use tests\codeception\frontend\unit\DbTestCase;
use tests\codeception\common\fixtures\UserFixture;
use Codeception\Specify;
use common\models\User;
use frontend\models\SignupForm;

class SignupTest extends DbTestCase
{

    use Specify;

    protected function setUp()
    {
        parent::setUp();
        Yii::$app->db->createCommand('TRUNCATE TABLE auth_assignment')->execute();
    }

    public function testCorrectSignup()
    {
        $model = Yii::createObject([
            'class' => 'frontend\models\SignupForm',
            'firstname' => 'Some',
            'lastname' => 'Username',
            'email' => 'some_email@example.com',
            'password' => 'some_password',
            'confirmPassword' => 'some_password',
            'category' => '3',
            'tcs' => '1',
        ]);

        $user = $model->signup();

        $this->assertInstanceOf('common\models\User', $user, 'user should be valid');

        expect('email should be correct', $user->email)->equals('some_email@example.com');
        expect('password should be correct', $user->validatePassword('some_password'))->true();
    }

    public function testNotCorrectSignup()
    {
        $model = new SignupForm([
        	'firstname' => 'Troy',
        	'lastname' => 'Becker',
            'email' => 'brady.renner@rutherford.com',
            'password' => 'some_password',
        	'confirmPassword' => 'some_password',
        	'category' => '3',
            'tcs' => '1',
        ]);

        expect('email is in use, user should not be created', $model->signup())->false();
    }

    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/frontend/unit/fixtures/data/models/user.php',
            ],
        ];
    }

}
