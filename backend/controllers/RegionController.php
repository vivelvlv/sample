<?php

namespace backend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\RegionCountry;
use common\models\RegionProvince;
use common\models\RegionCity;
use common\models\Region;
use yii\filters\AccessControl;


class RegionController extends BaseController
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

   //$id is parent id  of current level
    public function actionIndex($level,$id=0)
    {

        $regions = null;
        $count = 0;
        $country_id = 0;
        $province_id = 0;
        switch ($level) {
            case Region::COUNTRY_LEVEL:
                $regions = RegionCountry::find()->select(['country_id AS id','country_name AS name'])->asArray()->all();
                break;
            case Region::PROVINCE_LEVEL:
                $regions = RegionProvince::find()->select(['province_id AS id','province_name AS name'])->where(['country_id'=>$id])->asArray()->all();
                $country_id = $id;
                break;
            case Region::CITY_LEVEL:
                $regions = RegionCity::find()->select(['city_id AS id','city_name AS name'])->where(['province_id'=>$id])->asArray()->all();
                $province_id = $id;
                $provice = RegionProvince::findOne($id);
                if($provice != null)
                {
                    $country_id = $provice->country->country_id;
                }
                break;
            default:
                break;
        }
      

        return $this->render('index',[
                                        'regions'=>$regions,
                                        'level'=>$level,
                                        'country_id'=>$country_id,
                                        'province_id'=>$province_id
                                     ]);
    }

   //$id is parent id  of current level
    public function actionCreate($level,$id=0)
    {   
        $model = new Region();
        if($model->load(Yii::$app->request->post()))
        {
            if($model->createRegion($level,$id))
            {
                return $this->redirect(['index','level'=>$level,'id'=>$id]);
            }
        }
       return $this->renderAjax('create',['model'=>$model]);
    }

    public function actionDelete($level,$id,$delete_id)
    {
        $model = null;

        if($level == Region::COUNTRY_LEVEL)
        {
            $model = RegionCountry::findOne($delete_id);
        }
        else if($level == Region::PROVINCE_LEVEL)
        {
            $model = RegionProvince::findOne($delete_id);
        }
        else if($level == Region::CITY_LEVEL)
        {
            $model = RegionCity::findOne($delete_id);
        }
        if($model)
        {
            $model->delete();
            return $this->redirect(['index','level'=>$level,'id'=>$id]);
        }
        else
        {
             throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

       //$id is parent id  of current level
    public function actionUpdate($level,$id,$update_id)
    {   
        $model = new Region();
        $model->name = $model->getRegionName($level,$update_id);
        if($model->load(Yii::$app->request->post()))
        {
            if($model->updateRegion($level,$update_id))
            {
                return $this->redirect(['index','level'=>$level,'id'=>$id]);
            }
        }

       return $this->renderAjax('update',['model'=>$model]);
    }

    public function actionProvince($id)
    {

        $countProvinces = RegionProvince::find()->where(['country_id'=>$id])->count();
        $provinces = RegionProvince::find()->where(['country_id'=>$id])->all();
        if($countProvinces > 0)
        {
            foreach ($provinces as $province) {
                echo "<option value='".$province->province_id."'>".$province->province_name."</option>";
            }
        }
        else
        {
            echo "<option>-</option>";
        }
    }

    public function actionCity($id)
    {
        $countCities = RegionCity::find()->where(['province_id'=>$id])->count();
        $cities = RegionCity::find()->where(['province_id'=>$id])->all();
        if($countCities > 0)
        {
            foreach ($cities as $city) {
                echo "<option value='".$city->city_id."'>".$city->city_name."</option>";
            }
        }
        else
        {
            echo "<option>-</option>";
        }
    }


}
