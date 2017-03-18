<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $user_name;
    public $email;
    public $mobile_phone;
    public $inputPassword;
    public $reInputPassword;

    public $verifyCode;

    public $province;
    public $city;
    public $detail_address;

    public $company_name;
    public $company_tax;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['user_name', 'filter', 'filter' => 'trim'],
            [['user_name','mobile_phone','province', 'city','detail_address'], 'required'],
            ['user_name', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This user_name has already been taken.'],
            [['user_name','company_tax'], 'string', 'min' => 2, 'max' => 60],
            [['mobile_phone'], 'string', 'min' => 2, 'max' => 20],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [['email','detail_address'], 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['verifyCode','captcha'],

            [['inputPassword','reInputPassword'],'required','on'=>'create'],
            [['inputPassword','reInputPassword'],'string','min'=>6],
            ['reInputPassword','compare','compareAttribute'=>'inputPassword','message'=>Yii::t('frontend','Different Password')],

            [[ 'province', 'city'], 'integer'],
            [['company_name'], 'string', 'max' => 120],
            [['user_name', 'email', 'company_tax'], 'string', 'max' => 60],
        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_name' => Yii::t('frontend', 'User Name'),
            'password' => Yii::t('frontend', 'Password'),
            'verifyCode' => Yii::t('frontend', 'Verify Code'),

            'mobile_phone' => Yii::t('frontend', 'Mobile Phone'),
            'email' => Yii::t('frontend', 'Email'),
            'province' => Yii::t('frontend', 'Province'),
            'city' => Yii::t('frontend', 'City'),
            'detail_address' => Yii::t('frontend', 'Detail Address'),
            'company_name' => Yii::t('frontend', 'Company Name'),
            'company_tax' => Yii::t('frontend', 'Company Tax'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->user_name = $this->user_name;
        $user->email = $this->email;
        $user->mobile_phone = $this->mobile_phone;
        $user->province = $this->province;
        $user->city = $this->city;
        $user->detail_address = $this->detail_address;
        if(isset($this->company_name))
        {
            $user->company_name = $this->company_name;
        }

        if(isset($this->company_tax))
        {
            $user->company_tax = $this->company_tax;
        }

        $user->setPassword($this->inputPassword);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}


