<?php 

namespace frontend\controllers\individual;

use common\models\invoice\Quote;
use common\models\invoice\QuoteSearch;
use common\models\Status;

class QuoteController extends \common\controllers\IndividualController
{
	public $defaultAction = 'receive';


	public function actionReceive()
	{
		$request = $this->request;
		$params = $request->queryParams;
		$status = Quote::STATUS_SENT;
		$user_id = $this->userId;
		$ipAddr = $request->getUserIP();
		$searchModel = new QuoteSearch();
		$dataProvider = $searchModel->searchQuotesForIndividual($user_id, $params, $status);

		$this->audit->log($user_id, $this->action->uniqueId, 'ManageQuotes', 'Maintain quotes from billers', $ipAddr);

		return $this->render('received', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider
		]);
	}

	public function actionReject()
	{
		$request = $this->request;
		$params = $request->queryParams;
		$status = Quote::STATUS_REJECTED;
		$user_id = $this->userId;
		$ipAddr = $request->getUserIP();
		$searchModel = new QuoteSearch();
		$dataProvider = $searchModel->searchQuotesForIndividual($user_id, $params, $status);

		$this->audit->log($user_id, $this->action->uniqueId, 'ManageQuotes', 'Maintain quotes from billers', $ipAddr);

		return $this->render('rejected', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

    public function actionAccept()
    {
        $request = $this->request;
        $params = $request->queryParams;
        $statusId = Status::findOne(['code' => Status::STATUS_ACCEPTED])->id;
        $user_id = $this->userId;
        $ipAddr = $request->getUserIP();
        $searchModel = new QuoteSearch();
        $dataProvider = $searchModel->searchQuotesForIndividual($user_id, $params, $statusId);

        $this->audit->log($user_id, $this->action->uniqueId, 'ManageQuotes', 'Maintain quotes from billers', $ipAddr);

        return $this->render('accepted', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}