<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%complaint}}".
 *
 * @property integer $id
 * @property string $title
 * @property integer $user_id
 * @property integer $sample_service_id
 * @property string $content
 * @property integer $action_user
 * @property string $feedback
 * @property integer $created_at
 * @property integer $feedback_at
 * @property integer $status
 *
 * @property SampleService $sampleService
 */
class Complaint extends \yii\db\ActiveRecord
{

    const  COMPLAINT_STATUS_NO_START = 1;   //待处理
    const  COMPLAINT_STATUS_IN_PROCESS = 2; //处理中
    const  COMPLAINT_STATUS_COMPLETE = 3;   //已处理

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%complaint}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'user_id', 'sample_service_id', 'created_at', 'feedback_at'], 'required'],
            [['user_id', 'sample_service_id', 'action_user', 'created_at', 'feedback_at', 'status'], 'integer'],
            [['title'], 'string', 'max' => 150],
            [['content', 'feedback'], 'string', 'max' => 255],
            [['sample_service_id'], 'exist', 'skipOnError' => true, 'targetClass' => SampleService::className(),
                'targetAttribute' => ['sample_service_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'title' => Yii::t('common', 'Title'),
            'user_id' => Yii::t('common', 'User ID'),
            'sample_service_id' => Yii::t('common', 'Sample Service ID'),
            'content' => Yii::t('common', 'Content'),
            'action_user' => Yii::t('common', 'Action User'),
            'feedback' => Yii::t('common', 'Feedback'),
            'created_at' => Yii::t('common', 'Created At'),
            'feedback_at' => Yii::t('common', 'Feedback At'),
            'status' => Yii::t('common', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleService()
    {
        return $this->hasOne(SampleService::className(), ['id' => 'sample_service_id']);
    }

    public static function statusItems()
    {
        return [
            self::COMPLAINT_STATUS_NO_START => Yii::t('common', "not start"),
            self::COMPLAINT_STATUS_IN_PROCESS => Yii::t('common', "in progress"),
            self::COMPLAINT_STATUS_COMPLETE => Yii::t('common', "complete")

        ];
    }

    public function getActionUser()
    {
        return $this->hasone(AdminUser::className(), ['id' => 'action_user']);
    }

    public function getStatusText()
    {
        $text = self::statusItems();
        return isset($text[$this->status]) ? $text[$this->status] : "";
    }
}
