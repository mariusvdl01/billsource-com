<?php
namespace frontend\controllers\business;

use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use GuzzleHttp\Client;

class SubscriptionController extends Controller
{
       public function actionSubscribe()
    {
        $email = Yii::$app->request->post('email'); // from form
        $plan_code = 'PLN_crhjqo5vntgvd38'; // You must create this in Paystack dashboard or via API

        $client = new Client();

        try {
            $response = $client->post('https://api.paystack.co/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer YOUR_SECRET_KEY',
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'email' => $email,
                    'amount' => 462.23, // in kobo (₦5000)
                    'plan' => $plan_code,
                    'callback_url' => Url::to(['subscription/verify'], true),
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            return $this->redirect($result['data']['authorization_url']);
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Payment initialization failed: ' . $e->getMessage());
            return $this->goBack();
        }
    }

    public function actionVerify()
    {
        $reference = Yii::$app->request->get('reference');

        $client = new Client();
        $response = $client->get("https://api.paystack.co/transaction/verify/{$reference}", [
            'headers' => [
                'Authorization' => 'Bearer sk_test_c15369acbcbb990f3b0998b99b7b0cc512ee69ac',
            ],
        ]);

        $result = json_decode($response->getBody(), true);

        if ($result['data']['status'] === 'success') {
            // Save subscription info, mark as paid, etc.
            
            Yii::$app->session->setFlash('success', 'Payment successful!');
        } else {
            Yii::$app->session->setFlash('error', 'Payment verification failed!');
        }

        return $this->redirect(['site/index']);
    }
}
