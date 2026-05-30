<?php

namespace common\controllers;

use common\helpers\ArrayHelper;
use common\helpers\Billsource;
use common\models\business\BusinessClient;
use common\models\individual\IndividualClient;
use kartik\mpdf\Pdf;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;

class StatementController extends BaseController
{
    /**
     * Defines how certain actions can be executed irrespective of the default behavior
     *
     * @return array $actions defines actions behavior
     */
    public function actions()
    {
        return parent::actions();
    }

    /**
     * Defines behaviors to attach to actions in this class.
     *
     * @return array $behaviors an array of behaviors
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        ArrayHelper::merge([
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['reader', 'loader', 'singleUserAdmin', 'businessAdmin', 'individual'],
                    ],
                ],
            ],
        ], $behaviors);

        return $behaviors;
    }

    public function actionCreditor()
    {
        $model = null;
        $options = ['title' => 'Creditor Statement'];
        $type = '/pdf/statement';
        $billsource = new Billsource;
        $user = $this->user->identity;
        $message = 'Address details, registration, mobile and ID number is required to view invoices';

        $model = IndividualClient::findOne(['user_id' => $user->id]);
        if (!$model) {
            $model = BusinessClient::findOne(['user_id' => $user->id]);
        }

        if (!$model->hasCompleteProfile()) {
            $this->session->setFlash('info', $message);
            if ($user->business_user)
                return $this->redirect(['/business/profile/update']);
            else
                return $this->redirect(['/individual/profile/update']);
        }

        $dataset = $billsource->loadCreditorStatement($user);
        $pdf = Yii::$app->pdf;
        $header = Html::encode('Your professional Biller Service Provider') . '|| Account ledger (creditor)';
        $pdf->options = $options;
        $pdf->methods = ['SetHeader' => $header, 'SetFooter' => '{PAGENO}'];
        $htmlContent = $this->renderPartial($type, [
            'creditor' => $dataset['creditor'],
            'biller'  => $dataset['biller'],
            'invoices' => $dataset['invoices'],
            'options' => $options,
            'display' => true,
        ]);

        $pdf->content = $htmlContent;
        $pdf->destination = Pdf::DEST_DOWNLOAD;
        $pdf->filename = 'BILLSRC-CRT-STMT-' . date('Y-m-d', time()) . '.pdf';

        return $pdf->render();
    }

    public function actionDebtor()
    {
        $model = null;
        $options = ['title' => 'Debtor Statement'];
        $tpl = '/pdf/statement';
        $billsource = new Billsource;
        $user = $this->user->identity;
        $model = BusinessClient::findOne(['user_id' => $user->id]);
        $message = 'Address details, registration, mobile and ID number is required to view invoices';

        if (!$model->hasCompleteProfile()) {
            $this->session->setFlash('info', $message);
            return $this->redirect(['/business/profile/update']);
        }

        $dataset = $billsource->loadDebtorStatement($model);
        $pdf = Yii::$app->pdf;
        $header = Html::encode('Your professional Biller Service Provider') . '|| Account ledger (debtor)';
        $pdf->options = $options;
        $pdf->methods = ['SetHeader' => $header, 'SetFooter' => '{PAGENO}'];
        $htmlContent = $this->renderPartial($tpl, [
            'biller'  => $dataset['biller'],
            'invoices' => $dataset['invoices'],
            'options' => $options,
            'display' => false,
        ]);

        $pdf->content = $htmlContent;
        $pdf->destination = Pdf::DEST_DOWNLOAD;
        $pdf->filename = 'BILLSRC-DBT-STMT-' . date('Y-m-d', time()) . '.pdf';

        return $pdf->render();
    }
}