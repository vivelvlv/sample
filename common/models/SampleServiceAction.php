<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%sample_service_action}}".
 *
 * @property integer $id
 * @property integer $action_user
 * @property integer $status
 * @property string $comment
 * @property integer $sample_service_id
 * @property integer $created_at
 *
 * @property SampleService $sampleService
 */
class SampleServiceAction extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sample_service_action}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['action_user', 'sample_service_id', 'created_at'], 'required'],
            [['action_user', 'status', 'sample_service_id', 'created_at'], 'integer'],
            [['comment'], 'string', 'max' => 255],
            [['sample_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => SampleService::className(), 'targetAttribute' => ['sample_service_id' => 'id']],
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
            'status' => Yii::t('common', 'Status'),
            'comment' => Yii::t('common', 'Comment'),
            'sample_service_id' => Yii::t('common', 'Sample Service ID'),
            'created_at' => Yii::t('common', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleService()
    {
        return $this->hasOne(SampleService::className(), ['id' => 'sample_service_id']);
    }

    public static function log($action_user, $status, $sample_service_id,$comment='')
    {
        $action = new SampleServiceAction();
        $action->action_user = $action_user;
        $action->status = $status;
        $action->sample_service_id = $sample_service_id;
        $action->comment = $comment;
        $action->created_at = time();

        if($action->save())
        {
            return true;
        }
        else
        {
            if(!empty($action->errors))
            {
                Yii::error('Fail to log order action. Due to '. $action->errors[0]);
            } 
            return false;
        }
    }
}
