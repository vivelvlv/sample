<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%service_type}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $is_show
 */
class ServiceType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%service_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['is_show'], 'integer'],
            [['name'], 'string', 'max' => 60],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Name'),
            'description' => Yii::t('common', 'Description'),
            'is_show' => Yii::t('common', 'Is Show'),
        ];
    }

    /**
     * @inheritdoc
     * @return ServiceTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ServiceTypeQuery(get_called_class());
    }

    public static function typeAttributeLabel()
    {

        return ServiceType::find()->select(['name', 'id'])
            ->indexBy('id')
            ->column();
    }
}
