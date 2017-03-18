<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
/**
 * This is the model class for table "{{%auth_item}}".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 */
class Role extends \yii\db\ActiveRecord
{
  
    //temp use: create and update of role in the  controller
    public $permissions;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

     const CAT_ADMIN_USER  =  0;
     const CAT_USER        =  1;

     const CAT_SAMPLE      =  2;


     public static function categoryAttribute()
     {
       return [
          self::CAT_ADMIN_USER => Yii::t('common','Admin User Management'),
          self::CAT_USER =>Yii::t('common','User Management'),
          self::CAT_SAMPLE => Yii::t('common','Sample Management'),
       ];
     }

    public static function roleAttributes()
    {
        return [
           //Admin User Management
           'admin-user/index'=>Yii::t('common','Admin User Management'),
           'admin-user/create'=>Yii::t('common','Create Admin User'),
           'admin-user/update'=>Yii::t('common','Update Admin User'),

           'role/index'=>Yii::t('common','Role Management'),
           'role/create'=>Yii::t('common','Create Role'),
           'role/update'=>Yii::t('common','Update Role'),

           //User Management
           'user/index'=>Yii::t('common','User Management'),
           'user/update'=>Yii::t('common','Update User'),
           'user/signup'=>Yii::t('common','User Signup'),
           'user/basic-setting'=>Yii::t('common','Basic Setting'),

           //Sample Management
           'sample-service/receive'=>Yii::t('common','Receive Sample'),
           'sample-service/deliver'=>Yii::t('common','Deliver Sample'),
           'sample-service/normal-back'=>Yii::t('common','Sample Back'),
           'sample-service/exception-list'=>Yii::t('common','Exception List'),
           'sample-service/index'=>Yii::t('common','Sample Service List'),
           'sample-service/stuff-fetching'=>Yii::t('common','Stuff Fetching'),
           'sample-service/manage'=>Yii::t('common','Sample Management'),
           'sample/basic-setting'=>Yii::t('common','Basic Setting'),

       ];
    }

    public static function roleCategory()
    {
        return [
           //Admin User Management
           'admin-user/index'=>self::CAT_ADMIN_USER,
           'admin-user/create'=>self::CAT_ADMIN_USER,
           'admin-user/update'=>self::CAT_ADMIN_USER,

           'role/index'=>self::CAT_ADMIN_USER,
           'role/create'=>self::CAT_ADMIN_USER,
           'role/update'=>self::CAT_ADMIN_USER,

            //User Management
           'user/index'=>self::CAT_USER,
           'user/update'=>self::CAT_USER,
           'user/signup'=>self::CAT_USER,
           'user/basic-setting'=>self::CAT_USER,

           //Sample Management
           'sample-service/receive'=>self::CAT_SAMPLE,
           'sample-service/deliver'=>self::CAT_SAMPLE,
           'sample-service/normal-back'=>self::CAT_SAMPLE,
           'sample-service/exception-list'=>self::CAT_SAMPLE,
           'sample-service/stuff-fetching'=>self::CAT_SAMPLE,
           'sample-service/manage'=>self::CAT_SAMPLE,
           'sample/basic-setting'=>self::CAT_SAMPLE,
           'sample-service/index'=>self::CAT_SAMPLE,
       ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['permissions'],'required','on'=>'role_creation'],
            [['permissions'],'safe','on'=>'role_update'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
        ];
    }

    public static function dependPermissions()
    {
       return [
            //User basic setting
            'region-index'=>'user/basic-setting',

            //sample manage
            'sample-service/my-fetching'=>'sample-service/manage',
            'sample-service/my-test'=>'sample-service/manage',
            'sample-service/complete'=>'sample-service/manage',

            //sample basic setting
            'sample-type/index'=>'sample/basic-setting',
            'sample-type/create'=>'sample/basic-setting',
            'sample-type/view'=>'sample/basic-setting',
            'sample-type/update'=>'sample/basic-setting',

            'sample-unit/index'=>'sample/basic-setting',
            'sample-unit/create'=>'sample/basic-setting',
            'sample-unit/view'=>'sample/basic-setting',
            'sample-unit/update'=>'sample/basic-setting',

          ];
    }

    public static function getActualPermission( $permission)
    {
       $list = self::dependPermissions();
       if(isset($list[$permission]))
       {
          return $list[$permission];
       }
       else
       {
          return $permission;
       }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('common', 'Name'),
            'description' => Yii::t('common', 'Description'),
            'created_at' => Yii::t('common', 'Created At'),
            'updated_at' => Yii::t('common', 'Updated At'),
            'permissions'=> Yii::t('common', 'Permissions'),
        ];
    }

    public static function getPermissions()
    {
       $auth = Yii::$app->authManager;
       $authItems = $auth->getPermissions();
       return $authItems;
    }

    public static function getAuthItemsText()
    {
       $result =[];

       $auth = Yii::$app->authManager;
       $authItems = $auth->getPermissions();
       $authItemsText = Role::roleAttributes();     
       foreach ($authItems as  $authItem) 
       {
           if(isset($authItemsText[$authItem->name]))
           {
               $result[$authItem->name] = $authItemsText[$authItem->name];
           }
           else
           {
              $result[$authItem->name] = $authItem->name;
           }
           
       }
       return $result;
    } 
}
