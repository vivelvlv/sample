<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%sample}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $test_sheet_id
 * @property integer $user_id
 * @property string $serial_number
 * @property string $weight
 * @property integer $unit
 * @property integer $type
 * @property string $comment
 * @property string $project_sn
 * @property integer $status
 * @property integer $created_at
 * @property integer $completed_at
 *
 * @property User $user
 * @property TestSheet $testSheet
 * @property SampleAction[] $sampleActions
 * @property SampleService[] $sampleServices
 */
class Sample extends \yii\db\ActiveRecord
{
    /**
     * Status
     */
    const SAMPLE_STATUS_NO_SUBMIT = 0;
    const SAMPLE_STATUS_SUBMIT = 1;
    const SAMPLE_STATUS_RECEIVE = 2;
    const SAMPLE_STATUS_IN_TEST = 3;
    const SAMPLE_STATUS_COMPLETE = 4;
    const SAMPLE_STATUS_EXCEPTION = 5;

    public $sample_services;
    public $sample_services_hidden;
    public $unit_hidden;
    public $type_hidden;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sample}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::SAMPLE_STATUS_NO_SUBMIT],
            [['name', 'weight', 'sample_services', 'type'], 'required'],
            ['sample_services_hidden', 'required', 'message' => "."],
            [['test_sheet_id', 'user_id', 'type', 'status', 'created_at', 'completed_at'], 'integer'],
            [['weight'], 'number'],
            [['document'], 'string', 'max' => 255],
            [['name', 'project_sn'], 'string', 'max' => 120],
            [['serial_number'], 'string', 'max' => 60],
            ['serial_number', 'safe'],
            [['comment'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['test_sheet_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestSheet::className(), 'targetAttribute' => ['test_sheet_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document' => Yii::t('common', 'Document'),
            'id' => Yii::t('common', 'ID'),
            'name' => Yii::t('common', 'Sample Name'),
            'test_sheet_id' => Yii::t('common', 'Test Sheet'),
            'user_id' => Yii::t('common', 'User ID'),
            'serial_number' => Yii::t('common', 'Serial Number'),
            'weight' => Yii::t('common', 'Sample Weight'),
            'unit' => Yii::t('common', 'Unit'),
            'type' => Yii::t('common', 'Sample Type'),
            'comment' => Yii::t('common', 'Comment'),
            'status' => Yii::t('common', 'Status'),
            'created_at' => Yii::t('common', 'Created At'),
            'completed_at' => Yii::t('common', 'Completed At'),
            'sample_services' => Yii::t('common', 'Sample Services'),
            'unit_hidden' => Yii::t('common', 'Unit'),
            'type_hidden' => Yii::t('common', 'Sample Type'),
            'project_sn' => Yii::t('common', 'Project SN'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSheet()
    {
        return $this->hasOne(TestSheet::className(), ['id' => 'test_sheet_id']);
    }

    public function getTestSheetName()
    {
        return $this->getTestSheet()->one()->name;
    }


    public static function getTestSheetsKv()
    {

        return TestSheet::find()->select(['name', 'id'])
            ->indexBy('id')
            ->column();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleType()
    {
        return $this->hasOne(SampleType::className(), ['id' => 'type']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleUnit()
    {
        return $this->hasOne(SampleUnit::className(), ['id' => 'unit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleActions()
    {
        return $this->hasMany(SampleAction::className(), ['sample_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleServices()
    {
        return $this->hasMany(SampleService::className(), ['sample_id' => 'id']);
    }

    public static function statusItems()
    {
        return
            [
                self::SAMPLE_STATUS_NO_SUBMIT => Yii::t('common', 'No Submit'),
                self::SAMPLE_STATUS_SUBMIT => Yii::t('common', 'Submitted'),
                self::SAMPLE_STATUS_RECEIVE => Yii::t('common', 'Received'),
                self::SAMPLE_STATUS_IN_TEST => Yii::t('common', 'In Test'),
                self::SAMPLE_STATUS_COMPLETE => Yii::t('common', 'Completed'),
                self::SAMPLE_STATUS_EXCEPTION => Yii::t('common', 'Exception'),
            ];
    }

    public function getStatusText()
    {
        $texts = self::statusItems();
        return isset($texts[$this->status]) ?
            $texts[$this->status] : '';
    }

    public function getDocumentTitle()
    {
        if (isset($this->document) && !empty($this->document)) {
            $pos = strpos($this->document, "_");
            return substr($this->document, $pos + 1);
        } else {
            return '';
        }

    }
}

