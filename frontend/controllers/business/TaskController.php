<?php

namespace frontend\controllers\business;
use common\controllers\BusinessController;
use common\models\business\BusinessClient;
use common\models\business\BusinessEmployee;
use common\models\invoice\Task;
use common\models\invoice\TaskSearch;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\models\Status;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\document\BillerDocumentFactory;
use common\models\invoice\TaskLineManager;
use common\models\business\BusinessClientCrm as Crm;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends BusinessController
{
    // public $defaultAction = 'planning';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        ArrayHelper::merge([
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ], $behaviors);

        return $behaviors;
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search($this->client, $this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // echo'<pre/>';print_r($this->request);die();
        $audit = $this->audit;
        $request = $this->request;
        $ip = $request->getUserIP();
        $factory = new BillerDocumentFactory;
        $task = $factory->makeTask();
        $lineManager = new TaskLineManager($task);
        $biller = BusinessClient::findOne(['user_id' => $this->userId]);
        $employees = BusinessEmployee::getEmployees($biller->id);
        $task->business_id = $biller->id;
        if($request->isPost) {
            $task->load($request->Post(), 'Task');
            if($task->validate() &&  $task->save(false)) {
                $lineManager->manage($task, $request->post('TaskLine'));
                if($lineManager->validateLineItems() && $lineManager->saveLineItems($task)) {
                    $explode = explode(' ', $request->post('Task')['alt_business_name']);
                    $task->sendEmail($this->userId, $explode[0], $explode[1], $request->post('Task')['client_email'], $request->post('Task')['tname']);
                    return $this->redirect(['view', 'id' => $task->getPrimaryKey()]);
                }
                return $this->redirect(['index']);
            } else {
                $audit->log($this->userId, $this->action->uniqueId, 'NewTaskCreate', 'Error while creating new task', $ip);
            }
        }

        $task->reference_number = $this->referenceNumberGenerator();
        return $this->render('create', [
            'biller'		=> $biller,
            'employees'		=> $employees,
            'statuses'      => array(),
            'task' 		=> $task,
            'statuses'      => Status::findTaskStatuses(),
            'lineManager'	=> $lineManager,
            'openTasks'		=> Task::findOpenTask($this->client),
        ]);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

       /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'planning' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $url = str_replace('task_', '', $model->status->code);
        $model->delete();
        return $this->redirect([$url]);
    }

     /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $task = Task::findOne($id);
        $lineManager = new TaskLineManager($task);
        
        $biller = BusinessClient::findOne(['user_id' => $this->userId]);
        $employees = BusinessEmployee::getEmployees($biller->id);
        
        if($this->request->isPost) {
            $task->load($this->request->Post(), 'Task');
            if($task->validate())
            {
                $task->save(false);
                $lineManager->manage($task, $this->request->Post('TaskLine'));
                $lineManager->validate();
                $lineManager->saveLineItems($task);
                $this->session->setFlash('success', 'Task updated successfully');
                return $this->redirect(['view', 'id' => $task->getPrimaryKey()]);
            }
        }
        return $this->render('update', [
            'biller'		=> $biller,
            'employees'		=> $employees,
            'statuses'      => array(),
            'task' 		=> $task,
            'statuses'      => Status::findTaskStatuses(),
            'lineManager'	=> $lineManager,
            'openTasks'		=> Task::findOpenTask($this->client),
        ]);
    }

} 