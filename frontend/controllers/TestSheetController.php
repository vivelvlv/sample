<?php

namespace frontend\controllers;

use backend\models\SampleServiceSearch;
use common\models\Sample;
use common\models\SampleService;
use Yii;
use common\models\TestSheet;
use common\models\TestSheetSearch;
use frontend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\SampleSearch;
use yii\filters\AccessControl;
/**
 * TestSheetController implements the CRUD actions for TestSheet model.
 */
class TestSheetController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
          'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all TestSheet models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TestSheetSearch();
        if (!Yii::$app->user->identity->is_super) {
            $searchModel->user_id = Yii::$app->user->identity->id;
        }
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TestSheet model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        } else {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new TestSheet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TestSheet();
        $model->user_id = Yii::$app->user->identity->id;
        $model->storage_condition = TestSheet::STORAGE_ROOM_TEMPERATURE;
        $model->service_type = TestSheet::SERVICE_TYPE_REGULAR;
        $model->report_fetch_type = TestSheet::FETCH_REPORT_EMAIL;
        $model->sample_handle_type = TestSheet::SAMPLE_HANDLE_IN_LAB;
        $model->name = Yii::t('frontend', 'Test Page Order');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['add-sheet-samples', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }


//    public function actionCreateNewTestSheet()
//    {
//        $model = new TestSheet();
//        $model->user_id = Yii::$app->user->identity->id;
//        return $this->render('create-new-test-sheet');
//
//    }


    public function actionAddSheetSamples($id)
    {
        $searchModel = new SampleSearch();
        $searchModel->test_sheet_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $is_new = 1;  //add
        return $this->render('add-sheet-samples', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sheet_id' => $id,
            'is_new' => $is_new
        ]);
    }


    public function actionUpdateSheetSamples($id)
    {
        $searchModel = new SampleSearch();
        $searchModel->test_sheet_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $is_new = 0;  //update
        return $this->render('update-sheet-samples', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sheet_id' => $id,
            'is_new' => $is_new
        ]);
    }

    public function actionSubmit($id)
    {
        $model = $this->findModel($id);
        $model->status = TestSheet::TESTSHEET_STATUS_SUBMIT;
        $model->save();

        $samples = $model->getSamples()->all();
        foreach ($samples as $sample) {
            if ($sample->status == Sample::SAMPLE_STATUS_NO_SUBMIT) {
                $sample->status = Sample::SAMPLE_STATUS_SUBMIT;
                $sample->save();
            }
        }
        $sampleServices = $model->getSampleServices()->all();
        foreach ($sampleServices as $sampleservice) {
            if ($sampleservice->status == SampleService::SAMPLESERVICE_STATUS_NO_SUBMIT) {
                $sampleservice->status = SampleService::SAMPLESERVICE_STATUS_SUBMIT;
                $sampleservice->save();
            }
        }


        return $this->redirect(['index']);
    }

    /**
     * Updates an existing TestSheet model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => 'true'];
                } else {
                    return $this->redirect(['index']);
                }

            }

        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TestSheet model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TestSheet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TestSheet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TestSheet::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    /**
     * 打印测试单入口
     */
    public function actionPrint($id)
    {

        $extRows = Yii::$app->request->get("extRows");
        if (strlen($extRows) > 0) {
            $extRows = explode(",", $extRows);
        } else {
            $extRows = null;
        }
        $user = Yii::$app->user->identity;

        $testSheetData = TestSheet::find()->where(['id' => $id])->one();


        $searchModel = new SampleSearch();
        $searchModel->test_sheet_id = $id;

        $list = array();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if ($dataProvider->count > 0 && isset($dataProvider->models)) {
            foreach ($dataProvider->models as $item) {
                $printItem = new printItem();
                $printItem->id = $item->id;
                $printItem->name = $item->name;
                $printItem->serial_number = $item->serial_number;
                $printItem->weight = $item->weight;
                $printItem->comment = $item->comment;

                // TODO 性能不行?
                $servicesContentList = "";
                $sampleServicesModels = $item->getSampleServices()->all();
                foreach ($sampleServicesModels as $services_item) {
                    $service_detail = $services_item->getService()->one();
                    $servicesContentList .= $service_detail->catalog_number . ",";
//                    $service_detail->name;

                }

                if (strlen($servicesContentList) > 0) {
                    $printItem->serviceList = substr($servicesContentList, 0, strlen($servicesContentList) - 1);
                }

                array_push($list, $printItem);
            }
        }

        $this->layout = "print.php";
        return $this->render('print', ['barcode' => $testSheetData->barcode,
            'name' => $testSheetData->name,
            'user' => $user,
            'extRows' => $extRows,
            'printList' => $list,
            'testSheet' => $testSheetData
        ]);
    }


}

class printItem
{
    public $id;
    public $weight;
    public $name;
    public $serial_number;
    public $serviceList;
    public $comment;
}
