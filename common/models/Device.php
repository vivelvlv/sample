<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%device}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $is_show
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%device}}';
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

   public static function deviceAttributeLabel()
    {

       return Device::find()->select(['name','id'])
                         ->indexBy('id')
                         ->column();
      
    }
}
