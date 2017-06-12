<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%sample_service}}".
 *
 * @property integer $id
 * @property integer $sample_id
 * @property integer $service_id
 * @property integer $action_user
 * @property string $document
 * @property integer $created_at
 * @property integer $received_at
 * @property integer $completed_at
 * @property integer $status
 *
 * @property Complaint[] $complaints
 * @property AdminUser $actionUser
 * @property Sample $sample
 * @property SampleServiceAction[] $sampleServiceActions
 */
class SampleService extends \yii\db\ActiveRecord
{
    /**
     * Status
     */
    const SAMPLESERVICE_STATUS_NO_SUBMIT = 0;
    const SAMPLESERVICE_STATUS_SUBMIT = 1;
    const SAMPLESERVICE_STATUS_NO_DELIVER = 2;
    const SAMPLESERVICE_STATUS_IN_DELIVER = 3;
    const SAMPLESERVICE_STATUS_IN_TEST = 4;
    const SAMPLESERVICE_STATUS_COMPLETE = 5;
    const SAMPLESERVICE_STATUS_IN_EXCEPTION_BACK = 6;
    const SAMPLESERVICE_STATUS_IN_NORMAL_BACK = 7;
    const SAMPLESERVICE_STATUS_IN_COMPLAINT = 8;
    const SAMPLESERVICE_STATUS_EXCEPTION_BACK = 9;

    public $complainNumbers = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sample_service}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::SAMPLESERVICE_STATUS_NO_SUBMIT],
            [['sample_id', 'test_sheet_id', 'service_id', 'action_user', 'created_at', 'received_at', 'completed_at', 'status', 'user_id'], 'integer'],
            [['document'], 'string', 'max' => 255],
            [['barcode'], 'string', 'max' => 120],
            [['action_user'], 'exist', 'skipOnError' => true, 'targetClass' => AdminUser::className(), 'targetAttribute' => ['action_user' => 'id']],
            [['sample_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sample::className(), 'targetAttribute' => ['sample_id' => 'id']],
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
            'sample_id' => Yii::t('common', 'Sample & Serial Number'),
            'service_id' => Yii::t('common', 'Service'),
            'action_user' => Yii::t('common', 'Action User'),
            'document' => Yii::t('common', 'Download Document'),
            'created_at' => Yii::t('common', 'Date'),
            'completed_at' => Yii::t('common', 'Completed At'),
            'received_at' => Yii::t('common', 'Received At'),
            'status' => Yii::t('common', 'Status'),
            'test_sheet_id' => Yii::t('common', 'Test Sheet'),
            'user_id' => Yii::t('common', 'User'),
            'barcode' => Yii::t('common', 'Barcode'),
            'barcodes' => Yii::t('common', 'Barcode'),
            'deliver_barcodes' => Yii::t('common', 'Barcode'),
            'fetch_barcodes' => Yii::t('common', 'Fetching Barcodes')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComplaints()
    {
        return $this->hasMany(Complaint::className(), ['sample_service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getActionUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'action_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSample()
    {
        return $this->hasOne(Sample::className(), ['id' => 'sample_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSheet()
    {
        return $this->hasOne(TestSheet::className(), ['id' => 'test_sheet_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleServiceActions()
    {
        return $this->hasMany(SampleServiceAction::className(), ['sample_service_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComplainsActions()
    {
        return $this->hasMany(Complaint::className(), ['sample_service_id' => 'id']);
    }

    public function getStatusText()
    {
        $texts = self::statusItems();
        return isset($texts[$this->status]) ?
            $texts[$this->status] : '';
    }

    public static function statusItems()
    {
        return
            [
                self::SAMPLESERVICE_STATUS_NO_SUBMIT => Yii::t('common', 'No Submit'),
                self::SAMPLESERVICE_STATUS_SUBMIT => Yii::t('common', 'Submit'),
                self::SAMPLESERVICE_STATUS_NO_DELIVER => Yii::t('common', 'No Deliver'),
                self::SAMPLESERVICE_STATUS_IN_DELIVER => Yii::t('common', 'In Deliver'),
                self::SAMPLESERVICE_STATUS_IN_TEST => Yii::t('common', 'In Test'),
                self::SAMPLESERVICE_STATUS_COMPLETE => Yii::t('common', 'Complete'),
                self::SAMPLESERVICE_STATUS_IN_EXCEPTION_BACK => Yii::t('common', 'In Exception Back'),
                self::SAMPLESERVICE_STATUS_IN_NORMAL_BACK => Yii::t('common', 'In Normal Back'),
                self::SAMPLESERVICE_STATUS_IN_COMPLAINT => Yii::t('common', 'In Complaint'),
                self::SAMPLESERVICE_STATUS_EXCEPTION_BACK => Yii::t('common', 'Exception Back'),
            ];
    }
}
