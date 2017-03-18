<?php

namespace common\models;

use Yii;
use yii\base\Model;
use common\models\RegionCountry;
use common\models\RegionProvince;
use common\models\RegionCity;

/**
*  use to create and update the region.
*/
class Region extends Model
{
  const  COUNTRY_LEVEL = 1;
  const  PROVINCE_LEVEL = 2; 
  const  CITY_LEVEL = 3;

  //region name
  public $name;

  public function rules()
  {
    return [
        [['name'], 'required'],
        [['name'], 'string', 'max' => 255],
    ];
  }


  public function attributeLabels()
  {
      return [
          'name' => Yii::t('common', 'Name'),
      ];
  }

  public function createRegion($level,$parent_id)
  {
    if(empty($this->name))
    {
      return false;
    }

    if($level == self::COUNTRY_LEVEL)
    {
       $model = new RegionCountry();
       $model->country_name = $this->name;
       if($model->save())
       {
        return true;
       }
    }
    else if($level == self::PROVINCE_LEVEL)
    {
      $model = new RegionProvince();
      $model->province_name = $this->name;
      $model->country_id = $parent_id;
      if($model->save())
      {
        return true;
      }
    }
    else if($level == self::CITY_LEVEL)
    {
      $model = new RegionCity();
      $model->province_id = $parent_id;
      $model->city_name = $this->name;
      if($model->save())
      {
        return true;
      }
    }
    return false;
  }

  public function updateRegion($level,$region_id)
  {
    if(empty($this->name))
    {
      return false;
    }

    if($level == self::COUNTRY_LEVEL)
    {
       $model = RegionCountry::findOne($region_id);
       $model->country_name = $this->name;
       if($model->save())
       {
        return true;
       }
    }
    else if($level == self::PROVINCE_LEVEL)
    {
      $model = RegionProvince::findOne($region_id);
      $model->province_name = $this->name;
      if($model->save())
      {
        return true;
      }
    }
    else if($level == self::CITY_LEVEL)
    {
      $model = RegionCity::findOne($region_id);
      $model->city_name = $this->name;
      if($model->save())
      {
        return true;
      }
    }
    return false;
  }

  public function getRegionName($level,$region_id)
  {
      if($level == Region::COUNTRY_LEVEL)
      {
          $region = RegionCountry::findOne($region_id);
          return $region->country_name;
      }
      else if($level == Region::PROVINCE_LEVEL)
      {
          $region = RegionProvince::findOne($region_id);
          return $region->province_name;
      }
      else if($level == Region::CITY_LEVEL)
      {
          $region = RegionCity::findOne($region_id);
          return $region->city_name;
      }
      return '';
  }
}