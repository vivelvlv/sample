<?php

namespace backend\controllers;

use common\models\Complaint;
use common\models\ComplaintSearch;
use common\models\Sample;
use common\models\Service;
use common\models\TestSheet;
use Yii;
use common\models\SampleService;
use common\models\SampleServiceAction;
use backend\models\SampleServiceSearch;
use backend\models\SampleServiceMyTestSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SampleServiceController implements the CRUD actions for SampleService model.
 */
class SampleServiceController extends BaseController
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
     * Lists all SampleService models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SampleServiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $models = $dataProvider->getModels();
        foreach ($models as $model) {
            $complainList = $model->getComplainsActions()->all();
            $localFlag = false;
            foreach ($complainList as $complain) {
                if ($complain->status == Complaint::COMPLAINT_STATUS_COMPLETE) {
                    $localFlag = false;
                } else {
                    $localFlag = true;
                    break;
                }
            }
            $model->complainNumbers = $localFlag == true ? 1 : 0;
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SampleService model.
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

    /**
     * Creates a new SampleService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SampleService();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
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

    public function actionUpload($id)
    {
        $model = $this->findModel($id);

        return $this->renderAjax('upload-file', [
            'model' => $model,
        ]);
    }

    public function actionReceive()
    {
        $searchModel = new SampleServiceSearch();
        $searchModel->status = SampleService::SAMPLESERVICE_STATUS_SUBMIT;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('receive', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // 待确认的,正常回库的列表

    public function actionDeliver()
    {
        $searchModel = new SampleServiceSearch();
        $searchModel->status = SampleService::SAMPLESERVICE_STATUS_NO_DELIVER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('deliver', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // 待确认的,异常回库列表

    public function actionNormalBack()
    {
        $searchModel = new SampleServiceSearch();
        $searchModel->status = SampleService::SAMPLESERVICE_STATUS_IN_NORMAL_BACK;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('normal-back', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // 异常库列表

    public function actionExceptionBack()
    {
        $searchModel = new SampleServiceSearch();
        $searchModel->status = SampleService::SAMPLESERVICE_STATUS_IN_EXCEPTION_BACK;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('normal-back', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMyFetching()
    {
        $searchModel = new SampleServiceSearch();
        $searchModel->status = SampleService::SAMPLESERVICE_STATUS_IN_DELIVER;
        $searchModel->action_user = Yii::$app->user->identity->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('my-fetching', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * @return string
     * 显示我已领用的列表
     */
    public function actionMyTest()
    {
        $searchModel = new SampleServiceMyTestSearch();
        $searchModel->action_user = Yii::$app->user->identity->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $models = $dataProvider->getModels();
        foreach ($models as $model) {
            $complainList = $model->getComplainsActions()->all();
            $localFlag = false;
            foreach ($complainList as $complain) {
                if ($complain->status == Complaint::COMPLAINT_STATUS_COMPLETE) {
                    $localFlag = false;
                } else {
                    $localFlag = true;
                    break;
                }
            }
            $model->complainNumbers = $localFlag == true ? 1 : 0;
        }
        return $this->render('my-test', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionComplete()
    {
        $searchModel = new SampleServiceSearch();
        $searchModel->status = SampleService::SAMPLESERVICE_STATUS_COMPLETE;
        $searchModel->action_user = Yii::$app->user->identity->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('complete', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing SampleService model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionReceivePassMultiple()
    {
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }

        $models = SampleService::findAll(['id' => $pk]);


        foreach ($models as $model) {
            $sample = $model->getSample()->one();
            if ($sample->status == Sample::SAMPLE_STATUS_SUBMIT) {
                $sample->status = Sample::SAMPLE_STATUS_RECEIVE;
                $sample->save(false);
            }
            $testSheetModel = $sample->getTestSheet()->one();
            if ($testSheetModel->status == TestSheet::TESTSHEET_STATUS_SUBMIT) {
                $testSheetModel->status = TestSheet::TESTSHEET_STATUS_RECEIVE;
                $testSheetModel->save();
            }
            $model->received_at = time();
            $model->status = SampleService::SAMPLESERVICE_STATUS_NO_DELIVER;
            $sample = Sample::findOne(['id' => $model->sample_id]);
            $services = Service::findOne(['id' => $model->service_id]);
            $model->barcode = substr($sample->user_id . "-" . $services->catalog_number . "-" . $model->id, 0, 100);
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }
        return 1;
    }

    public function actionReceiveDenyMultiple()
    {
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }
        $models = SampleService::findAll(['id' => $pk]);

        foreach ($models as $model) {
            $model->status = SampleService::SAMPLESERVICE_STATUS_EXCEPTION_BACK;
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }
        return 1;
    }

    public function actionFetchMultiple()
    {
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }

        $models = SampleService::findAll(['id' => $pk]);

        foreach ($models as $model) {
            if ($model->status == SampleService::SAMPLESERVICE_STATUS_IN_TEST) {
                continue;
            }
            $model->status = SampleService::SAMPLESERVICE_STATUS_IN_TEST;
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }
        return 1;
    }

    public function actionDeliverPassMultiple()
    {
        $pk = Yii::$app->request->post('pk');
        $adu = Yii::$app->request->post('adu');

        if (!$pk || !$adu) {
            return 0;
        }

        $models = SampleService::findAll(['id' => $pk]);

        $barcode_str = "";
        foreach ($models as $model) {

            $barcode_str .= $model->barcode . "\n";
            if ($model->status == SampleService::SAMPLESERVICE_STATUS_IN_DELIVER) {
                continue;
            }
            $model->status = SampleService::SAMPLESERVICE_STATUS_IN_DELIVER;
            $model->action_user = $adu;
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }


        return 1;
    }

    public function actionPrintBarcodes($id)
    {

        if (!$id) {
            return 0;
        }

        $id = explode(",", $id);

        $models = SampleService::findAll(['id' => $id]);

        $barcode_str = "测试条码\n";
        foreach ($models as $model) {
            $barcode_str .= $model->barcode . "\n";
        }

        $filename = time() . '.csv'; //设置文件名
        $this->export_csv($filename, $barcode_str); //导出
    }

    private function export_csv($filename, $data)
    {
        $data = mb_convert_encoding($data, "gb2312", "UTF-8");
        header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . time() . ".xls ");
        header("Content-Transfer-Encoding: binary ");
        echo $data;
    }

    public function actionPrintAll($regin)
    {
        $exec = new Excel();
        $data = [array("时间", "样品名", "测试项", "状态", "客户", "样品备注")];
        $regin = urldecode($regin);
        if (isset($regin) && strlen($regin) > 0 && strpos($regin, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $regin);
            $models = SampleService::find()->where(['between', SampleService::tableName() . ".created_at",
                strtotime($start_date),
                strtotime($end_date) + 24 * 60 * 60])->all();
        } else {
            $models = SampleService::find()->all();
        }
        foreach ($models as $model) {
            $time = date("Y/m/d H:i", $model->created_at);
            $sample = $model->getSample();
            $sample_name = "";
            $note = "";
            $object = null;
            if (isset($sample)) {
                $object = $sample->one();
                if (isset($object)) {
                    $sample_name = $object->name;
                    $note = $object->comment;
                }
            }
            $service = $model->getService();
            $serivce_name = "";
            $object = null;
            if (isset($service)) {
                $object = $service->one();
                if (isset($object)) {
                    $serivce_name = $object->name;
                }
            }
            $status = $model->getStatusText();
            $user = $model->getUser();

            $user_name = "";
            if (isset($user)) {
                $object = $user->one();
                if (isset($object)) {
                    $user_name = $object->user_name;
                }
            }


            array_push($data, [$time, $sample_name, $serivce_name, $status, $user_name, $note]);
        }

        $exec->download($data, time() . "");
    }

    public function actionCompletePassMultiple()
    {
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }

        $models = SampleService::findAll(['id' => $pk]);

        foreach ($models as $model) {
            if ($model->status == SampleService::SAMPLESERVICE_STATUS_IN_TEST) {

            } else {
                return -1;
            }
        }

        foreach ($models as $model) {
            if ($model->status != SampleService::SAMPLESERVICE_STATUS_IN_TEST) {
                continue;
            }

            $model->status = SampleService::SAMPLESERVICE_STATUS_COMPLETE;
            $model->completed_at = time();
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }
        return 1;
    }

    public function actionNormalBackMultiple()
    {
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }

        $models = SampleService::findAll(['id' => $pk]);

        foreach ($models as $model) {
            if ($model->status == SampleService::SAMPLESERVICE_STATUS_IN_DELIVER
                || $model->status == SampleService::SAMPLESERVICE_STATUS_IN_TEST
            ) {

            } else {
                return -1;
            }
        }

        foreach ($models as $model) {

            $model->status = SampleService::SAMPLESERVICE_STATUS_IN_NORMAL_BACK;
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }
        return 1;
    }

    // 分析员->发起->正常回库

    public function actionExceptionBackMultiple()
    {
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }

        $models = SampleService::findAll(['id' => $pk]);

        foreach ($models as $model) {
            if ($model->status == SampleService::SAMPLESERVICE_STATUS_IN_DELIVER
                || $model->status == SampleService::SAMPLESERVICE_STATUS_IN_TEST
            ) {

            } else {
                return -1;
            }
        }


        foreach ($models as $model) {

            $model->status = SampleService::SAMPLESERVICE_STATUS_IN_EXCEPTION_BACK;
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }
        return 1;
    }

    // 分析员->发起->异常回库

    public function actionExceptionBackPassMultiple()
    {
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }

        $models = SampleService::findAll(['id' => $pk]);

        foreach ($models as $model) {

            $model->status = SampleService::SAMPLESERVICE_STATUS_EXCEPTION_BACK;
            $model->action_user = null;
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }
        return 1;

    }

    // 样品管理员->接收->异常退库

    public function actionNormalBackPassMultiple()
    {
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }

        $models = SampleService::findAll(['id' => $pk]);

        foreach ($models as $model) {
            $model->status = SampleService::SAMPLESERVICE_STATUS_NO_DELIVER;
            $model->action_user = null;
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }
        return 1;
    }

    // 样品管理员->接收->正常退库

    public function actionExceptionToNormal($id)
    {
        $model = SampleService::findOne(['id' => $id]);
        $model->status = SampleService::SAMPLESERVICE_STATUS_NO_DELIVER;
        $model->action_user = null;
        if ($model->save()) {
            SampleServiceAction::log(Yii::$app->user->identity->id,
                $model->status,
                $model->id
            );
        }

        $this->actionExceptionList();

    }

    // 异常到正常

    public function actionExceptionList()
    {
        $searchModel = new SampleServiceSearch();
        $searchModel->status = SampleService::SAMPLESERVICE_STATUS_EXCEPTION_BACK;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('exception-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStuffFetching()
    {
        $searchModel = new SampleServiceSearch();
        $searchModel->status = SampleService::SAMPLESERVICE_STATUS_IN_DELIVER;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('stuff-fetching', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStuffFetchPassMultiple()
    {
        $adu = Yii::$app->request->post('adu');
        $pk = Yii::$app->request->post('pk');
        if (!$pk) {
            return 0;
        }

        $models = SampleService::findAll(['id' => $pk]);

        foreach ($models as $model) {
            if ($model->status == SampleService::SAMPLESERVICE_STATUS_IN_TEST) {
                continue;
            }
            $model->status = SampleService::SAMPLESERVICE_STATUS_IN_TEST;
            if ($adu > 0) {
                $model->action_user = $adu;
            }
            if ($model->save()) {
                SampleServiceAction::log(Yii::$app->user->identity->id,
                    $model->status,
                    $model->id
                );
            }
        }
        return 1;
    }

    public function actionUpdateComplain($id)
    {
        $model = Complaint::findOne($id);
        $model->feedback_at = time();
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');

        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $sampleService = $model->getSampleService()->one();
            $complainList = $sampleService->getComplaints()->all();
            $localFlag = false;
            foreach ($complainList as $complain) {
                if ($complain->status == Complaint::COMPLAINT_STATUS_COMPLETE) {
                    $localFlag = false;
                } else {
                    $localFlag = true;
                    break;
                }
            }
            if ($localFlag == false) {
                $sampleService->status = SampleService::SAMPLESERVICE_STATUS_COMPLETE;
            } else {
                $sampleService->status = SampleService::SAMPLESERVICE_STATUS_IN_COMPLAINT;
            }
            $sampleService->save();


            return $this->actionComplainlist($model->getSampleService()->one()->id);
        }

        $this->layout = 'simple_layout';
        return $this->render('_form_complain_update', ['model' => $model]);

    }

    public function actionComplainlist($id)
    {
        $complaintList = new ComplaintSearch();
        $complaintList->sample_service_id = $id;
        $dataProvider = $complaintList->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('complain-list', [
                'searchModel' => $complaintList,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            return $this->render('complain-list', [
                'searchModel' => $complaintList,
                'dataProvider' => $dataProvider,
            ]);
        }


    }

}

/**
 * 生成excel文件操作
 *
 * @author wesley wu
 * @date 2013.12.9
 */
class Excel
{

    private $limit = 10000;

    public function download($data, $fileName)
    {
        $fileName = $this->_charset($fileName);
        header("Content-Type: application/vnd.ms-excel; charset=gbk");
        header("Content-Disposition: inline; filename=\"" . $fileName . ".xls\"");
        echo "<?xml version=\"1.0\" encoding=\"gbk\"?>\n
            <Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"
            xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
            xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"
            xmlns:html=\"http://www.w3.org/TR/REC-html40\">";
        echo "\n<Worksheet ss:Name=\"" . $fileName . "\">\n<Table>\n";
        $guard = 0;
        foreach ($data as $v) {
            $guard++;
            if ($guard == $this->limit) {
                ob_flush();
                flush();
                $guard = 0;
            }
            echo $this->_addRow($this->_charset($v));
        }
        echo "</Table>\n</Worksheet>\n</Workbook>";
    }

    private function _charset($data)
    {
        if (!$data) {
            return false;
        }
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->_charset($v);
            }
            return $data;
        }
        return iconv('utf-8', 'gbk', $data);
    }

    private function _addRow($row)
    {
        $cells = "";
        foreach ($row as $k => $v) {
            $cells .= "<Cell><Data ss:Type=\"String\">" . $v . "</Data></Cell>\n";
        }
        return "<Row>\n" . $cells . "</Row>\n";
    }

}

