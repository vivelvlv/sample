<?php

namespace backend\controllers;

use Yii;
use common\models\AdminUser;
use common\models\AdminUserSearch;
use backend\controllers\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

/**
 * AdminUserController implements the CRUD actions for User model.
 */
class AdminUserController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminUser();
        $model->status = AdminUser::STATUS_ACTIVE;
        $model->scenario = 'create';
        
        if ($model->load(Yii::$app->request->post())) 
        {
            if($model->validate())
            {

              if(isset($model->inputPassword))
               {
                  $model->password = $model->inputPassword;
               }
               //upload the image
               $saveName = time().rand(100,999);
               
               $res = Yii::$app->upload->UploadImage($model,'image','uploads/avatar/');
               if( $res !== false)
               {
                  $model->image = $res;
               }
               $model->entry_date= strtotime($model->entry_date);
                if($model->save(false))
                {
                   $auth = Yii::$app->authManager;
                   $role = Yii::$app->authManager->getRole($model->role);
                   if($role != null)
                   {
                     $auth->assign($role,$model->id);
                   }
                   else
                   {
                     throw new NotFoundHttpException('Can not find role item.');
                   }

                    return $this->redirect(['view', 'id' => $model->id]);
                }  
            }
        } 

        return $this->render('create', [
                'model' => $model,
            ]);

    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $model->entry_date= Yii::$app->formatter->asDate($model->entry_date, 'yyyy-MM-dd');
        if(isset($model->leave_date))
        {
            $model->leave_date= Yii::$app->formatter->asDate($model->leave_date, 'yyyy-MM-dd');
        }

        $oldImage = $model->image;
        $oldLeader = $model->leader_id;

        $auth = Yii::$app->authManager;
        $oldRoleArray = ArrayHelper::getColumn($auth->getRolesByUser($model->id),'name');
        $oldRole = empty($oldRoleArray) ? '':current($oldRoleArray);
        $model->role = $oldRole;


        if ($model->load(Yii::$app->request->post())) 
        {
            if($model->validate())
            {

               if(isset($model->inputPassword))
               {
                  $model->password = $model->inputPassword;
               }
               
              //upload the image
              $isOldImage = false;
              $res = Yii::$app->upload->UploadImage($model,'image','uploads/avatar/');
              if( $res !== false)
              {
                 $model->image = $res;
              }
              else
              {
                 if(isset($oldImage) && !empty($oldImage))
                 {
                    $isOldImage = true;
                    $model->image = $oldImage;
                 }
              }

              $model->entry_date= strtotime($model->entry_date);
              if(isset($model->leave_date) && !empty($model->leave_date))
              {
                 $model->leave_date= strtotime($model->leave_date);
              }
    
             if($model->role != $oldRole)
             {

                $oldRole = Yii::$app->authManager->getRole($oldRole);
                if($oldRole != null)
                    $auth->revoke($oldRole,$model->id);

                $newRole = Yii::$app->authManager->getRole($model->role);
                if($newRole != null)
                   $auth->assign($newRole,$model->id);

             }

              if($model->save(false))
              {
                if(!$isOldImage )
                {
                   Yii::$app->upload->removeImage($oldImage);
                }
                 return $this->redirect(['view', 'id' => $model->id]);
              }  
            }              
        } 

         return $this->render('update', [
                'model' => $model,
            ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminUser::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionSend()
    {
        $mail= Yii::$app->mailer->compose();   
        $mail->setTo('511542984@qq.com');  
        $mail->setSubject("邮件测试");  
        //$mail->setTextBody('zheshisha ');   //发布纯文字文本
        $mail->setHtmlBody("<br>问我我我我我");    //发布可以带html标签的文本
        if($mail->send())  
            print_r("success");  
        else  
            print_r("failure");  
        die(); 
    }
}
