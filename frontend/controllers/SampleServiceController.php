<?php

namespace frontend\controllers;

use common\models\Complaint;
use common\models\ComplaintSearch;
use Yii;
use common\models\SampleService;
use frontend\models\SampleServiceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SampleServiceController implements the CRUD actions for SampleService model.
 */
class SampleServiceController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all SubProject models.
     * @return mixed
     */
    public function actionIndex()
    {
        //If use renderPartial, it seems the new added javascript not work.

        if (isset($_POST['expandRowKey'])) {
            $this->layout = "simple_layout.php";
            $searchModel = new SampleServiceSearch();
            $searchModel->sample_id = $_POST['expandRowKey'];
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {

            $searchModel = new SampleServiceSearch();
            $searchModel->user_id = Yii::$app->user->identity->id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index_new', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

    }

    /**
     * Displays a single SampleService model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $model,
            ]);
        } else {
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }


    public function actionComplain($id)
    {
        $complain = new Complaint();
        $complain->user_id = Yii::$app->user->identity->id;
        $complain->sample_service_id = $id;
        $complain->created_at = time();
        $complain->status = Complaint::COMPLAINT_STATUS_NO_START;
        $complain->feedback_at = time();
        $complain->title = Yii::t("frontend", "Complain Reason");


        if (($first = $complain->load(Yii::$app->request->post())) && ($second = $complain->save())) {
            $sampleService = SampleService::findOne($complain->sample_service_id);
            if ($sampleService != null) {
                $sampleService->status = SampleService::SAMPLESERVICE_STATUS_IN_COMPLAINT;
                $sampleService->save();
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('complain-submit-result', [
                    'model' => $complain,
                ]);
            } else {
                return $this->render('complain-submit-result', [
                    'model' => $complain,
                ]);
            }
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('complain', [
                    'model' => $complain,
                ]);
            } else {
                return $this->render('complain', [
                    'model' => $complain,
                ]);
            }
        }
    }

    public function actionComplainlist($id)
    {
        $this->layout = "simple_layout.php";
        $complaintList = new ComplaintSearch();
        $complaintList->sample_service_id = $id;
        $dataProvider = $complaintList->search(Yii::$app->request->queryParams);
        return $this->render('complain-list', [
            'searchModel' => $complaintList,
            'dataProvider' => $dataProvider,
        ]);

    }


    /**
     * Updates an existing SampleService model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    /**
     * Finds the SampleService model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SampleService the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SampleService::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
