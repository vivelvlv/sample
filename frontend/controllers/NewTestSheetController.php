<?php

namespace frontend\controllers;

use common\models\Sample;
use common\models\DynamicFormModel;
use common\models\SampleSearch;
use common\models\SampleService;
use common\models\TestSheetSearch;
use common\models\User;
use Exception;
use frontend\models\SampleServiceForm;
use Yii;
use common\models\TestSheet;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\controllers\BaseController;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * NewTestSheetController implements the CRUD actions for TestSheet model.
 */
class NewTestSheetController extends BaseController
{
    /**
     * @inheritdoc
     */

    private $create2Print = false;

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
            $this->layout = "print.php";
            return $this->actionDetailInfo($id);
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

        $modelSamples = [new Sample];

        $model->user_id = Yii::$app->user->identity->id;
        $userInfo = Yii::$app->user->identity;
        $model->storage_condition = TestSheet::STORAGE_ROOM_TEMPERATURE;
        $model->service_type = TestSheet::SERVICE_TYPE_REGULAR;
        $model->report_fetch_type = TestSheet::FETCH_REPORT_EMAIL;
        $model->sample_handle_type = TestSheet::SAMPLE_HANDLE_IN_LAB;
        $model->name = Yii::t('frontend', 'Test Page Order');


        if ($model->load(Yii::$app->request->post())) {

            $modelSamples = DynamicFormModel::createMultiple(Sample::className());
            DynamicFormModel::loadMultiple($modelSamples, Yii::$app->request->post());
            $valid = $model->validate();

            $valid = DynamicFormModel::validateMultiple($modelSamples) && $valid;

            if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        $row = 0;
                        foreach ($modelSamples as $modelSample) {
                            $modelSample->test_sheet_id = $model->id;
                            $modelSample->user_id = $model->user_id;

                            $instanceName = 'Sample[' . $row . '][document]';
                            $file = UploadedFile::getInstanceByName($instanceName);

                            if (!empty($file)) {
                                $saveName = $model->user_id . time() . rand(100, 999) . '_' . $file->name;
                                $res = Yii::$app->upload->UploadFileInstance($file, 'uploads/sample/', $saveName);
                                if ($res !== false) {
                                    $modelSample->document = $res;
                                }
                            }

                            if (!($flag = $modelSample->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                            $sample_services = explode(",", $modelSample->sample_services_hidden);
                            $modelSampleServiceForm = new SampleServiceForm();
                            $modelSampleServiceForm->sample_id = $modelSample->id;
                            $modelSampleServiceForm->services = $modelSampleServiceForm->getServiceItems($modelSampleServiceForm->sample_id);

                            $modelSampleServiceForm->updateSampleServices($modelSampleServiceForm->services, $sample_services);
                            $row++;
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        // 创建成功
                        $this->create2Print = true;
                        return $this->actionPrint($model->id);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'userInfo' => $userInfo,
                'modelSamples' => $modelSamples
            ]);
        }
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
        $userInfo = Yii::$app->user->identity;
        $modelSamples = $model->getSamples()->all();

        if ($model->status != TestSheet::TESTSHEET_STATUS_RECEIVE) {
            $model->status = TestSheet::TESTSHEET_STATUS_WITHDRAW;
        } else {
            return;
        }

        $oldFiles = [];
        foreach ($modelSamples as $modelSample) {
            $oldFiles[] = [
                'id' => $modelSample->id,
                'file' => $modelSample->document,
                'created_at' => $modelSample->created_at
            ];

        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            ///
            $oldIDs = ArrayHelper::map($modelSamples, 'id', 'id');
            $modelSamples = DynamicFormModel::createMultiple(Sample::classname(), $modelSamples);
            DynamicFormModel::loadMultiple($modelSamples, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelSamples, 'id', 'id')));

            $valid = $model->validate();

            $valid = DynamicFormModel::validateMultiple($modelSamples) && $valid;

            if ($valid) {

                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            Sample::deleteAll(['id' => $deletedIDs]);
                        }
                        $row = 0;
                        foreach ($modelSamples as $modelSample) {
                            $modelSample->test_sheet_id = $model->id;
                            $modelSample->user_id = $model->user_id;
                            $modelSample->status = Sample::SAMPLE_STATUS_NO_SUBMIT;

                            //upload the image
                            $instanceName = 'Sample[' . $row . '][document]';
                            $file = UploadedFile::getInstanceByName($instanceName);

                            if (!empty($file)) {
                                $saveName = $model->user_id . time() . rand(100, 999) . '_' . $file->name;

                                $res = Yii::$app->upload->UploadFileInstance($file, 'uploads/sample/', $saveName);
                                if ($res !== false) {
                                    $modelSample->document = $res;
                                }
                            } else {
                                if (isset($modelSample->id)) {
                                    foreach ($oldFiles as $oldFile) {

                                        if ($oldFile['id'] == $modelSample->id &&
                                            $oldFile['created_at'] == $modelSample->created_at
                                        ) {

                                            if (isset($oldFile['file']) && !empty($oldFile['file'])) {
                                                $modelSample->document = $oldFile['file'];
                                            }
                                            break;
                                        }
                                    }
                                }

                            }
                            if (!($flag = $modelSample->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                            $sample_services = explode(",", $modelSample->sample_services_hidden);
                            $modelSampleServiceForm = new SampleServiceForm();
                            $modelSampleServiceForm->sample_id = $modelSample->id;
                            $modelSampleServiceForm->services = $modelSampleServiceForm->getServiceItems($modelSampleServiceForm->sample_id);

                            $modelSampleServiceForm->updateSampleServices($modelSampleServiceForm->services, $sample_services);
                            $row++;
                        }
                    }
                    if ($flag) {
                        $transaction->commit();

                        return $this->actionPrint($model->id);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

            ///


            return $this->actionPrint($model->id);;
        } else {

            foreach ($modelSamples as $modelSample) {
                $modelSampleServices = $modelSample->getSampleServices()->all();
                $ids = [];
                $names = [];
                foreach ($modelSampleServices as $item) {
                    array_push($ids, $item->getService()->one()->id);
                    array_push($names, $item->getService()->one()->name);
                }

                $modelSample->sample_services = implode(",", $names);
                $modelSample->sample_services_hidden = implode(",", $ids);

//                $sampleUnit = $modelSample->getSampleUnit()->one();
//                $modelSample->unit_hidden = $sampleUnit->name;
                $modelSample->type_hidden = $modelSample->getSampleType()->one()->name;
            }

            return $this->render('update', [
                'model' => $model,
                'userInfo' => $userInfo,
                'modelSamples' => $modelSamples
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

    public function actionChangeUser()
    {
        $user_id = Yii::$app->request->post("adu");
        $user = User::findOne(['id' => $user_id]);
        return Json::encode($user);
    }


    public function actionDetailInfo($id)
    {
        $testSheetData = TestSheet::find()->where(['id' => $id])->one();
        $user = Yii::$app->user->identity;
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
                $printItem->weight = $item->weight ;
                $printItem->comment = $item->comment;

                $servicesContentList = "";
                $sampleServicesModels = $item->getSampleServices()->all();
                foreach ($sampleServicesModels as $services_item) {
                    $service_detail = $services_item->getService()->one();
                    $servicesContentList .= $service_detail->name . ",";
//                    $service_detail->name;

                }

                if (strlen($servicesContentList) > 0) {
                    $printItem->serviceList = substr($servicesContentList, 0, strlen($servicesContentList) - 1);
                }

                array_push($list, $printItem);
            }
        }

        return $this->render('detail-view', ['barcode' => $testSheetData->barcode,
            'name' => $testSheetData->name,
            'user' => $user,
            'extRows' => "",
            'printList' => $list,
            'testSheet' => $testSheetData
        ]);

    }

    /**
     * 打印测试单入口
     */
    public function actionPrint($id)
    {

        $create2Print = false;
        if ($this->create2Print == true) {
            $create2Print = true;
            $this->create2Print = false;
        }

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

//        $this->layout = "print.php";
        return $this->render('print', ['barcode' => $testSheetData->barcode,
            'name' => $testSheetData->name,
            'user' => $user,
            'extRows' => $extRows,
            'printList' => $list,
            'id' => $id,
            'create2Print' => $create2Print,
            'testSheet' => $testSheetData
        ]);
    }

    public function actionPrintSubmit()
    {

        $modelId = Yii::$app->request->post("pk");
        if (isset($modelId) && is_int($modelId + 0)) {
            $model = $this->findModel($modelId);
            if ($model->status != TestSheet::TESTSHEET_STATUS_RECEIVE) {
                $model->status = TestSheet::TESTSHEET_STATUS_SUBMIT;
            }
            $model->save();

            $samples = $model->getSamples()->all();

            foreach ($samples as $sample) {

                if ($sample->status == Sample::SAMPLE_STATUS_NO_SUBMIT) {
                    $sample->status = Sample::SAMPLE_STATUS_SUBMIT;
                    $sample->save(false);

                }
            }
            $sampleServices = $model->getSampleServices()->all();
            foreach ($sampleServices as $sampleservice) {
                if ($sampleservice->status == SampleService::SAMPLESERVICE_STATUS_NO_SUBMIT) {
                    $sampleservice->status = SampleService::SAMPLESERVICE_STATUS_SUBMIT;
                    $sampleservice->save();
                }
            }
        }
        return "success->" . $modelId;
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
