<?php

namespace backend\controllers;

use Yii;
use common\models\SampleType;
use common\models\SampleTypeSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
/**
 * SampleTypeController implements the CRUD actions for SampleType model.
 */
class SampleTypeController extends BaseController
{
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all SampleType models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SampleTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SampleType model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if(Yii::$app->request->isAjax)
        {
            return $this->renderAjax('view', [
                'model' => $this->findModel($id),
            ]);
        }
       else
       {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
       }
    }

    /**
     * Creates a new SampleType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SampleType();
        $model->is_show =1;
        if ($model->load(Yii::$app->request->post()) ) 
        {
            if($model->save())
            {
                if(Yii::$app->request->isAjax)
                {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success'=>'true'];
                }
                return $this->redirect(['index']);
            }
            
        }
        if(Yii::$app->request->isAjax)
        {
             return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
       else
       {
            return $this->render('create', [
                    'model' => $model,
                ]);
       }
    }

    /**
     * Updates an existing SampleType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) 
        {
            if($model->save())
            {
                if(Yii::$app->request->isAjax)
                {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success'=>'true'];
                }
                return $this->redirect(['index']);
            }
            
        }
        if(Yii::$app->request->isAjax)
        {
             return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
       else
       {
            return $this->render('update', [
                    'model' => $model,
                ]);
       }
        
    }

    /**
     * Deletes an existing SampleType model.
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
     * Finds the SampleType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SampleType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SampleType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
