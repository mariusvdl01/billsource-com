<?php
/**
 * Created by PhpStorm.
 * User: netcraft
 * Date: 12/13/15
 * Time: 7:17 PM
 */

namespace api\modules\v1\controllers;

use api\modules\v1\models\SignupForm;
use api\modules\v1\models\User;

class UserController extends AbstractBaseController
{
    public $modelClass = 'api\modules\v1\models\User';

    public $defaultAction = 'login';

    public function actionLogin()
    {
        $post = json_decode(file_get_contents('php://input'), true);
        $model = User::findByEmail($post['email']);
        if(empty($model)) {
            return [
                'message' => 'Invalid login credentials. Check your email/password',
            ];
        } 

        if ($model->validatePassword($post['password'])) {
            $model->last_login = (new \DateTime())->format('Y-m-d H:i:s');
            $model->save(false);

            $networth = 0.0;
            $businessUser = $model->business_user;
            $client = $businessUser ? $model->businessClient : $model->individualClient;
            if(!$businessUser) {
                $ratio = $this->calculateRatios($client->individualFinancials);
                $networth = number_format($ratio['assets'] - $ratio['liabilities'], 2);
            }
           

            return [
                'auth_key' => $model->auth_key,
                'type' => $businessUser,
                'client' => $client,
                'progress' => ($client->completed / User::EXPECTED_FIELD) * 100,
                'networth' => $networth
            ];
        }

        return [
            'message' => 'Invalid login credentials. Check your email/password',
        ];
    }

    protected function calculateRatios($indFinancials)
    {
        $financials = $indFinancials;
        $assets = 0.0;
        $liabilities = 0.0;
        $surplus = 0.0;

        foreach ($financials as $value) {
            $assets += $value->total_assets;
            $liabilities += $value->total_liabilities;
            $surplus += $value->surplus;
        }

        return $ratio  = [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'surplus' => $surplus
        ];
    }

    public function actionRegister()
    {
        $model = new SignupForm();
        $post = json_decode(file_get_contents('php://input'), true);

        if ($model->load($post()) && $model->validate()) {

            if (($user = $model->signup()) !== false) {
                $model->sendEmail();
                return [
                    'success' => true,
                    'message' => 'Please check your email to confirm your email.'
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Server error encountered. Please try again'
        ];
    }
}