<?php

namespace frontend\controllers;

use common\models\TestSheet;
use Yii;
use common\models\Sample;
use common\models\SampleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;
use frontend\models\SampleServiceForm;
use yii\filters\AccessControl;

/**
 * SampleController implements the CRUD actions for Sample model.
 */
class SampleController extends BaseController
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
     * Lists all Sample models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SampleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sample model.
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
     * Creates a new Sample model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sample();
        $model->test_sheet_id = Yii::$app->request->get('sheet_id');
        $testsheet = TestSheet::findOne(['id' => $model->test_sheet_id]);
        $model->user_id = $testsheet->user_id;
        $is_new = Yii::$app->request->get('is_new');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => 'true'];
                }
                if ($is_new == 1) {
                    return $this->redirect(['test-sheet/add-sheet-samples', 'id' => $model->test_sheet_id]);
                } else {
                    return $this->redirect(['test-sheet/update-sheet-samples', 'id' => $model->test_sheet_id]);
                }
            }

        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Sample model.
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
     * Creates a new SampleService model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionSampleService()
    {
        $model = new SampleServiceForm();
        $model->sample_id = Yii::$app->request->get('id');
        $model->services = $model->getServiceItems($model->sample_id);
        $oldServices = $model->services;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->updateSampleServices($oldServices, $model->services);

                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => 'true'];
                }
                return $this->redirect(['index']);
            }

        }
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('sample-service', [
                'model' => $model,
            ]);
        } else {
            return $this->render('sample-service', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Sample model.
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
     * Finds the Sample model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sample the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sample::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
