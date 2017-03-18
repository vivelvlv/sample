<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $user_name
 * @property string $mobile_phone
 * @property string $email
 * @property integer $province
 * @property integer $city
 * @property string $detail_address
 * @property string $prefix
 * @property string $company_name
 * @property string $company_tax
 * @property integer $last_login
 * @property integer $visit_count
 * @property integer $is_validated
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $status
 * @property integer $created_at
 * @property integer $is_super
 *
 * @property Sample[] $samples
 * @property TestSheet[] $testSheets
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;


    public $inputPassword;
    public $reInputPassword;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_name', 'auth_key', 'password_hash'], 'required'],
            [['province', 'city', 'last_login', 'visit_count', 'is_validated', 'status', 'created_at', 'is_super'], 'integer'],
            [['user_name', 'email', 'company_tax'], 'string', 'max' => 60],
            [['mobile_phone', 'prefix'], 'string', 'max' => 20],
            [['detail_address', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['company_name'], 'string', 'max' => 120],
            [['auth_key'], 'string', 'max' => 32],
            [['mobile_phone'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['inputPassword','reInputPassword'],'string','min'=>6],
            ['reInputPassword','compare','compareAttribute'=>'inputPassword','message'=>Yii::t('common','Different Password')],
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
            'user_name' => Yii::t('common', 'User Name'),
            'mobile_phone' => Yii::t('common', 'Mobile Phone'),
            'email' => Yii::t('common', 'Email'),
            'province' => Yii::t('common', 'Province'),
            'city' => Yii::t('common', 'City'),
            'detail_address' => Yii::t('common', 'Detail Address'),
            'prefix' => Yii::t('common', 'Prefix'),
            'company_name' => Yii::t('common', 'Company Name'),
            'company_tax' => Yii::t('common', 'Company Tax'),
            'last_login' => Yii::t('common', 'Last Login'),
            'visit_count' => Yii::t('common', 'Visit Count'),
            'status' => Yii::t('common', 'Status'),
            'created_at' => Yii::t('common', 'Created At'),
            'is_super' => Yii::t('common', 'Is Super'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSamples()
    {
        return $this->hasMany(Sample::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTestSheets()
    {
        return $this->hasMany(TestSheet::className(), ['user_id' => 'id']);
    }

    public function getUserProvince()
    {
        return $this->hasOne(RegionProvince::className(), ['province_id' => 'province']);
    }

    public function getUserCity()
    {
        return $this->hasOne(RegionCity::className(), ['city_id' => 'city']);
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

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
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


    public static function userAttributeLabel()
    {

        return User::find()->select(['user_name', 'id'])
            ->indexBy('id')
            ->column();

    }

    public function getAddress()
    {
        $province = $this->getUserProvince()->one();
        $city = $this->getUserCity()->one();
        $address = '';
        if (isset($province)) {
            $address .= $province->province_name . ' ';
        }
        if (isset($city)) {
            $address .= $city->city_name . ' ';
        }

        return $address . $this->detail_address;
    }
}
