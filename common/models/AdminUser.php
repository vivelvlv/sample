<?php

namespace common\models;

use Yii;

use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "{{%admin_user}}".
 *
 * @property integer $id
 * @property string $user_name
 * @property string $work_no
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property string $office_phone
 * @property string $mobile_phone
 * @property string $image
 * @property integer $leader_id
 * @property string $lab_building
 * @property string $lab_floor
 * @property string $lab_room
 * @property string $office_building
 * @property string $office_floor
 * @property string $office_room
 * @property integer $entry_date
 * @property integer $leave_date
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $is_super
 *
 * @property Article[] $articles
 * @property SampleService[] $sampleServices
 */
class AdminUser extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $inputPassword;
    public $reInputPassword;
    public $role;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['user_name', 'work_no',  'email'], 'required'],
            [['leader_id'],'safe','on'=>'init'],
            [['leader_id','role'],'required','on'=>'create'],
            [['leader_id','role'],'required','on'=>'update'],
            [['leader_id',  'created_at', 'updated_at', 'is_super'], 'integer'],
            [['user_name', 'password_hash', 'password_reset_token', 'email', 'image'], 'string', 'max' => 255],
            [['work_no'], 'string', 'max' => 10],
            [['office_phone', 'mobile_phone', 'lab_building', 'lab_floor', 'lab_room', 'office_building', 'office_floor', 'office_room'], 'string', 'max' => 20],
            [['auth_key'], 'string', 'max' => 32],
            [['work_no'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password_reset_token'], 'unique'],
            [['inputPassword','reInputPassword'],'required','on'=>'create'],
            [['inputPassword','reInputPassword'],'string','min'=>6],
            ['reInputPassword','compare','compareAttribute'=>'inputPassword','message'=>Yii::t('common','Different Password')],
            [['entry_date'],'required'],
            [['leave_date'],'safe'],
            ['leave_date','required','when'=>function($model){
                                                return $model->status == self::STATUS_DELETED;
                                         },
                                    'whenClient'=>"function(attribute,value){
                                        return $('#adminuser-status').val() == '0';
                                    }"
            ],
            [['image'],'safe'],
            [['image'], 'image',  'extensions' => 'png, gif,jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('common', 'ID'),
            'user_name' => Yii::t('common', 'User Name'),
            'work_no' => Yii::t('common', 'Work No'),
            'inputPassword' => Yii::t('common', 'Password'),
            'reInputPassword' => Yii::t('common', 'Re-Password'),
            'email' => Yii::t('common', 'Email'),
            'status' => Yii::t('common', 'Status'),
            'office_phone' => Yii::t('common', 'Office Phone'),
            'mobile_phone' => Yii::t('common', 'Mobile Phone'),
            'image' => Yii::t('common', 'Avatar'),
            'leader_id' => Yii::t('common', 'Leader'),
            'lab_building' => Yii::t('common', 'Lab Building'),
            'lab_floor' => Yii::t('common', 'Lab Floor'),
            'lab_room' => Yii::t('common', 'Lab Room'),
            'office_building' => Yii::t('common', 'Office Building'),
            'office_floor' => Yii::t('common', 'Office Floor'),
            'office_room' => Yii::t('common', 'Office Room'),
            'entry_date' => Yii::t('common', 'Entry Date'),
            'leave_date' => Yii::t('common', 'Leave Date'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'is_super' => Yii::t('common', 'Is Super'),
            'role'=>Yii::t('common', 'Role'),
        ];
    }

    // public function getShownEntryDate()
    // {
    //    return Yii::$app->formatter->asDate($this->entry_date, 'yyyy-MM-dd');
    // }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['created_by' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLeader()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'leader_id']);
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['user_name' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    public static function getStatusList()
    {
      return [
             AdminUser::STATUS_DELETED=>Yii::t('common','InActive'),
             AdminUser::STATUS_ACTIVE=>Yii::t('common','Active')
        ];
    }
    public function getStatusText()
    {
        return $this->status == AdminUser::STATUS_ACTIVE ? 
         Yii::t('common','Active'):Yii::t('common','InActive');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSampleServices()
    {
        return $this->hasMany(SampleService::className(), ['action_user' => 'id']);
    }

    public static function userAttributeLabel()
    {

       return AdminUser::find()->select(['user_name','id'])
                         ->indexBy('id')
                         ->column();
      
    }

    public function isOwn($permission)
    {
        if($this->is_super)
        {
            return true;
        }
        return \Yii::$app->user->can($permission);   
    }

    public function getRoleName()
    {
        $auth = Yii::$app->authManager;
        $result = ArrayHelper::getColumn($auth->getRolesByUser($this->id),'name');
        if(!empty($result))
        {
            return current($result);
        }
        else
        {
            return '';
        }
    }
}
