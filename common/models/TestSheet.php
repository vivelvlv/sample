<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%test_sheet}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $storage_condition
 * @property integer $service_type
 * @property integer $report_fetch_type
 * @property integer $sample_handle_type
 * @property integer $status
 * @property string $comment
 * @property string $barcode
 * @property integer $created_at
 * @property integer $completed_at
 *
 * @property Sample[] $samples
 * @property User $user
 */
class TestSheet extends \yii\db\ActiveRecord
{
    //status
    const TESTSHEET_STATUS_NO_SUBMIT = 1;
    const TESTSHEET_STATUS_SUBMIT = 2;
    const TESTSHEET_STATUS_WITHDRAW = 3;
    const TESTSHEET_STATUS_RECEIVE = 4;

    //Storage Condition
    const STORAGE_ROOM_TEMPERATURE = 1;
    const STORAGE_PLUS_4 = 2;
    const STORAGE_MINUS_20 = 3;
    const STORAGE_MINUS_80 = 4;


    //Service Type
    const SERVICE_TYPE_REGULAR = 1;
    const SERVICE_TYPE_URGENT = 2;

    //Fetch Report TYpe
    const FETCH_REPORT_EMAIL = 1;
    const FETCH_REPORT_SELF = 2;
    const FETCH_REPORT_POST = 3;

    //Sample Handle Type
    const SAMPLE_HANDLE_IN_LAB = 1;
    const SAMPLE_HANDLE_BACK_USER = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%test_sheet}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::TESTSHEET_STATUS_NO_SUBMIT],
            [['name', 'user_id'], 'required'],
            [['user_id', 'storage_condition', 'service_type', 'report_fetch_type', 'sample_handle_type', 'status', 'created_at', 'completed_at'], 'integer'],
            [['name', 'barcode'], 'string', 'max' => 120],
            [['comment'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => Yii::t('common', 'User ID'),
            'storage_condition' => Yii::t('common', 'Storage Condition'),
            'service_type' => Yii::t('common', 'Service Type'),
            'report_fetch_type' => Yii::t('common', 'Report Fetch Type'),
            'sample_handle_type' => Yii::t('common', 'Sample Handle Type'),
            'status' => Yii::t('common', 'Status'),
            'comment' => Yii::t('common', 'Comment'),
            'barcode' => Yii::t('common', 'Barcode'),
            'created_at' => Yii::t('common', 'Created At'),
            'completed_at' => Yii::t('common', 'Completed At'),
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->barcode = '1' . $this->user_id . time();
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSamples()
    {
        return $this->hasMany(Sample::className(), ['test_sheet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleServices()
    {
        return $this->hasMany(SampleService::className(), ['test_sheet_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function storageConditionItems()
    {
        return
            [
                self::STORAGE_ROOM_TEMPERATURE => Yii::t('common', 'Room Temperature'),
                self::STORAGE_PLUS_4 => Yii::t('common', '4℃'),
                self::STORAGE_MINUS_20 => Yii::t('common', '-20℃'),
                self::STORAGE_MINUS_80 => Yii::t('common', '-80℃'),
            ];
    }

    public function getStorageConditionText()
    {
        $texts = self::storageConditionItems();
        return isset($texts[$this->storage_condition]) ?
            $texts[$this->storage_condition] : '';
    }

    public static function serviceTypeItems()
    {
        return
            [
                self::SERVICE_TYPE_REGULAR => Yii::t('common', 'Regular'),
                self::SERVICE_TYPE_URGENT => Yii::t('common', 'Urgent'),
            ];
    }

    public function getServiceTypeText()
    {
        $texts = self::serviceTypeItems();
        return isset($texts[$this->service_type]) ?
            $texts[$this->service_type] : '';
    }

    public static function fetchReportTypeItems()
    {
        return
            [
                self::FETCH_REPORT_EMAIL => Yii::t('common', 'Email'),
                self::FETCH_REPORT_SELF => Yii::t('common', 'Help Yourself'),
                self::FETCH_REPORT_POST => Yii::t('common', 'Mail'),
            ];
    }

    public function getFetchReportTypeText()
    {
        $texts = self::fetchReportTypeItems();
        return isset($texts[$this->report_fetch_type]) ?
            $texts[$this->report_fetch_type] : '';
    }

    public static function sampleHandleTypeItems()
    {
        return
            [
                self::SAMPLE_HANDLE_IN_LAB => Yii::t('common', 'In Lab'),
                self::SAMPLE_HANDLE_BACK_USER => Yii::t('common', 'Back to User')
            ];
    }

    public function getSampleHandleTypeText()
    {
        $texts = self::sampleHandleTypeItems();
        return isset($texts[$this->sample_handle_type]) ?
            $texts[$this->sample_handle_type] : '';
    }

    public static function statusItems()
    {
        return
            [
                self::TESTSHEET_STATUS_NO_SUBMIT => Yii::t('common', 'No Submit'),
                self::TESTSHEET_STATUS_SUBMIT => Yii::t('common', 'Submitted'),
                self::TESTSHEET_STATUS_WITHDRAW => Yii::t('common', 'Withdraw'),
                self::TESTSHEET_STATUS_RECEIVE => Yii::t('common', 'Received'),
            ];
    }

    public function getStatusText()
    {
        $texts = self::statusItems();
        return isset($texts[$this->status]) ?
            $texts[$this->status] : '';
    }
}

