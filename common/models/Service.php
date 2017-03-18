<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\models\Device;

/**
 * This is the model class for table "{{%service}}".
 *
 * @property integer $id
 * @property string $catalog_number
 * @property string $name
 * @property string $description
 * @property string $price
 * @property integer $created_at
 * @property integer $device_id
 * @property string $comment
 * @property integer $is_show
 * @property integer $type
 *
 */
class Service extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%service}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['catalog_number', 'name', 'type'], 'required'],
            [['price'], 'number'],
            [['created_at', 'device_id', 'is_show', 'type'], 'integer'],
            [['catalog_number'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 60],
            [['description', 'comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'catalog_number' => Yii::t('common', 'Catalog Number'),
            'name' => Yii::t('common', 'Name'),
            'description' => Yii::t('common', 'Description'),
            'price' => Yii::t('common', 'Price'),
            'created_at' => Yii::t('common', 'Created At'),
            'device_id' => Yii::t('common', 'Device'),
            'comment' => Yii::t('common', 'Comment'),
            'is_show' => Yii::t('common', 'Is Show'),
            'type' => Yii::t('common', "Service Type"),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServiceType()
    {
        return $this->hasOne(ServiceType::className(), ['id' => 'type']);
    }

    public static function serviceItems()
    {

        return Service::find()->select(['name', 'id'])
            ->indexBy('id')
            ->column();

    }

    public static function serviceItemsList($id)
    {
        return Service::find()->select(["name", "id"])->where(['type' => $id])->indexBy('id')->column();
    }


}
