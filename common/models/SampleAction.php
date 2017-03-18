<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sample_action}}".
 *
 * @property integer $id
 * @property integer $action_user
 * @property integer $type
 * @property integer $status
 * @property string $comment
 * @property integer $sample_id
 * @property integer $created_at
 *
 * @property Sample $sample
 */
class SampleAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sample_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_user', 'sample_id', 'created_at'], 'required'],
            [['action_user', 'type', 'status', 'sample_id', 'created_at'], 'integer'],
            [['comment'], 'string', 'max' => 255],
            [['sample_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sample::className(), 'targetAttribute' => ['sample_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'action_user' => Yii::t('common', 'Action User'),
            'type' => Yii::t('common', 'Type'),
            'status' => Yii::t('common', 'Status'),
            'comment' => Yii::t('common', 'Comment'),
            'sample_id' => Yii::t('common', 'Sample'),
            'created_at' => Yii::t('common', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSample()
    {
        return $this->hasOne(Sample::className(), ['id' => 'sample_id']);
    }
}
