<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%region_country}}".
 *
 * @property integer $country_id
 * @property string $country_name
 * @property string $area_code
 *
 * @property RegionProvince[] $regionProvinces
 */
class RegionCountry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%region_country}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_name'], 'required'],
            [['country_name'], 'string', 'max' => 255],
            [['area_code'], 'string', 'max' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_id' => Yii::t('common', 'Country'),
            'country_name' => Yii::t('common', 'Country Name'),
            'area_code' => Yii::t('common', 'Area Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionProvinces()
    {
        return $this->hasMany(RegionProvince::className(), ['country_id' => 'country_id']);
    }
}
