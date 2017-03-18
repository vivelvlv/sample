<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InitController
 *
 * @author Joe
 */

namespace console\controllers;
use Yii;
use common\models\AdminUser;
use common\models\Role;


class InitController extends \yii\console\Controller
{
    //put your code here
    public function actionUser()
    {
        echo "Create init User ...\n";

        //ensure the new added user is first one
        if(AdminUser::find()->count() > 0)
        {
            echo "aleady have users in the datebase!\n";
            echo "Please clear the user table then continue.\n";
            return 0;
        }


        $username = $this->prompt('Input UserName:');
        $email = $this->prompt("Input Email for $username:");
        $password = $this->prompt("Input password for $username:");
        
        $model = new AdminUser();
        $model->scenario = 'init';
        $model->user_name = $username;
        $model->email = $email;
        $model->password = $password;
        $model->is_super = 2;
        $model->work_no = '000000';
        $model->entry_date = time();
        //$model->image='dist/img/avatar5.png';
        
        if(!$model->save())
        {
            foreach ($model->getErrors() as $errors)
            {
                foreach($errors as $e)
                {
                    echo "$e\n";
                }
            }
            return 0;
        }

        echo "Admin User:".$username." Successfully Created!";
        return 1;
    }

    

    // public function actionPermission()
    // {
    //     echo "init Permission ...\n";

    //     echo "If exist auth item, remove all.\n";
    //     Yii::$app->db->createCommand()->delete('{{%auth_item}}')->execute();

    //     $role_attributes = Role::roleAttributes();
    //     $insert_array = [];
    //     $time = time();
    //     foreach($role_attributes as $name => $desc) 
    //     { 
    //        $insert_array[] = [ $name, 2 , $desc,$time,$time];
    //     }
    //     Yii::$app->db->createCommand()->batchInsert('{{%auth_item}}',
    //                                                 ['name','type','description','created_at','updated_at'],
    //                                                 $insert_array
    //                                                 )
    //                                   ->execute();
    //     echo "init Permission Successfully ...\n";                                         
    // }

    //use for increase premission base on the Role::roleAttributes
    public function actionIncPermission()
    {
        echo "increase Permission ...\n";

        $authItems = Role::getPermissions();

        $role_attributes = Role::roleAttributes();
        $insert_array = [];
        $time = time();
        foreach($role_attributes as $name => $desc) 
        { 
           $bFind = false;
           foreach ($authItems as $authItem) 
           {
              if($authItem->name === $name)
              {
                 $bFind = true;
                 break;
              }
           }
           if(!$bFind)
           {
              $insert_array[] = [ $name, 2 , $desc,$time,$time];
              echo $name."\n"; 
           }      
        }
        Yii::$app->db->createCommand()->batchInsert('{{%auth_item}}',
                                                    ['name','type','description','created_at','updated_at'],
                                                    $insert_array
                                                    )
                                      ->execute();
        echo "increase Permission Successfully ...\n"; 
    }
}
