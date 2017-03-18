<?php

namespace backend\controllers;

use Yii;
use common\models\Role;
use common\models\RoleSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 */
class RoleController extends BaseController
{
    /**
     * @inheritdoc
     */
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            ''
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $auth = Yii::$app->authManager;

        $model = $this->findModel($id);
        $model->scenario = 'role_update';
        $oldPermissions =ArrayHelper::getColumn($auth->getPermissionsByRole($model->name),'name');
        $oldName = $model->name;
        $model->permissions = $oldPermissions;

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role();
        $model->scenario = 'role_creation';

        if ($model->load(Yii::$app->request->post())) 
        {
            $auth = Yii::$app->authManager;
            if($auth != null && $model->validate())
            {
                //Create role
                $role = $auth->createRole($model->name);
                $role->description= $model->description;
                $auth->add($role);

                //assign child 
                foreach($model->permissions as $value)
                {
                    $child = $auth->getPermission($value);
                    if($child != null)
                    {
                        $auth->addChild($role,$child);
                    }
                    else
                    {
                        throw new NotFoundHttpException('can not find auth item');
                    }
                }
                return $this->redirect(['view', 'id' => $model->name]);
            }
            
        } 
          return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $auth = Yii::$app->authManager;

        $model = $this->findModel($id);
        $model->scenario = 'role_update';
        $oldPermissions =ArrayHelper::getColumn($auth->getPermissionsByRole($model->name),'name');
        $oldName = $model->name;
        $model->permissions = $oldPermissions;
        if ($model->load(Yii::$app->request->post())) 
        {

            if($auth != null && $model->validate())
            {
                $role = $auth->getRole($oldName);
                $role->description = $model->description;

                //Add or Rmove Child Permissions
                $rmPermissions=array_diff($oldPermissions, $model->permissions);
                $addPermissions = array_diff($model->permissions, $oldPermissions);
                if(!empty($rmPermissions))
                {
                    foreach($rmPermissions as $value)
                    {
                        $child = $auth->getPermission($value);
                        if($child != null)
                        {
                            $auth->removeChild($role,$child);
                        }
                    }
                }
                if(!empty($addPermissions))
                {
                   foreach($addPermissions as $value)
                    {
                        $child = $auth->getPermission($value);
                        if($child != null)
                        {
                            $auth->addChild($role,$child);
                        }
                    }
                }

                $auth->update($model->name,$role);
                return $this->redirect(['view', 'id' => $model->name]);
            }
            
        } 

         return $this->render('update', [
                'model' => $model,
            ]);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
