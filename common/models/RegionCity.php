<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%region_city}}".
 *
 * @property integer $city_id
 * @property string $city_name
 * @property string $area_code
 * @property integer $province_id
 *
 * @property RegionArea[] $regionAreas
 * @property RegionProvince $province
 */
class RegionCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%region_city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['city_name'], 'required'],
            [['province_id'], 'integer'],
            [['city_name'], 'string', 'max' => 255],
            [['area_code'], 'string', 'max' => 6],
            [['province_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegionProvince::className(), 'targetAttribute' => ['province_id' => 'province_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'city_id' => Yii::t('common', 'City'),
            'city_name' => Yii::t('common', 'City Name'),
            'area_code' => Yii::t('common', 'Area Code'),
            'province_id' => Yii::t('common', 'Province'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionAreas()
    {
        return $this->hasMany(RegionArea::className(), ['city_id' => 'city_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvince()
    {
        return $this->hasOne(RegionProvince::className(), ['province_id' => 'province_id']);
    }
}
