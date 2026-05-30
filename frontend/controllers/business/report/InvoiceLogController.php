<?php

namespace frontend\controllers\business\report;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\invoice\InvoiceLog;
use common\models\invoice\InvoiceLogSearch;
use common\controllers\BusinessController;

/**
 * InvoiceLogController implements the CRUD actions for InvoiceLog model.
 */
class InvoiceLogController extends BusinessController
{
    /**
     * Lists all InvoiceLog models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceLogSearch();
        $dataProvider = $searchModel->search($this->client, $this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
