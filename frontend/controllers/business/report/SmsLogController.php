<?php

namespace frontend\controllers\business\report;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\sms\SmsLog;
use common\models\sms\SmsLogSearch;
use common\controllers\BusinessController;

/**
 * SmsLogController implements the CRUD actions for SmsLog model.
 */
class SmsLogController extends BusinessController
{
    /**
     * Lists all SmsLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsLogSearch();
        $dataProvider = $searchModel->search($this->client, $this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
