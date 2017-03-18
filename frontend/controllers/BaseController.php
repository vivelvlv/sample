<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Role;

/**
 * Base controller
 * All other backend controller should inherit from this class.
 */
class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action))
        {
            return false;
        }

        // //if the user is super, he owns all privileges.
        // if(Yii::$app->user->identity != null)
        // {
        //     if(Yii::$app->user->identity->is_super) 
        //     {
        //        return true;
        //     }
        // }

        // $controller = Yii::$app->controller->id;
        // $action = Yii::$app->controller->action->id;
        // $permissionName = $controller.'/'.$action;  

        // $permissionName = Role::getActualPermission($permissionName);

        // $auth = Yii::$app->authManager;
        // if($auth != null && $auth->getPermission($permissionName) != null)
        // {
        //   if(!\Yii::$app->user->can($permissionName) && Yii::$app->getErrorHandler()->exception === null)
        //   {
        //     throw new \yii\web\UnauthorizedHttpException(Yii::t('app','Sorry, no permission for this operation'));
        //    }
        // }

        return true;
    }
}
