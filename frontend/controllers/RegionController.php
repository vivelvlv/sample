<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\RegionCountry;
use common\models\RegionProvince;
use common\models\RegionCity;
use common\models\Region;
use yii\web\Controller;


class RegionController extends BaseController
{
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
