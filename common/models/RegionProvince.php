<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%region_province}}".
 *
 * @property integer $province_id
 * @property string $province_name
 * @property string $area_code
 * @property integer $country_id
 *
 * @property RegionCity[] $regionCities
 * @property RegionCountry $country
 */
class RegionProvince extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%region_province}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_name'], 'required'],
            [['country_id'], 'integer'],
            [['province_name'], 'string', 'max' => 255],
            [['area_code'], 'string', 'max' => 6],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegionCountry::className(), 'targetAttribute' => ['country_id' => 'country_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'province_id' => Yii::t('common', 'Province'),
            'province_name' => Yii::t('common', 'Province Name'),
            'area_code' => Yii::t('common', 'Area Code'),
            'country_id' => Yii::t('common', 'Country'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionCities()
    {
        return $this->hasMany(RegionCity::className(), ['province_id' => 'province_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(RegionCountry::className(), ['country_id' => 'country_id']);
    }
}
