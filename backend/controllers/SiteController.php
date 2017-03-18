<?php
namespace backend\controllers;

use common\models\Article;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\LoginForm;
use yii\filters\VerbFilter;
use common\models\AdminUser;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class SiteController extends BaseController
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
                        'actions' => ['login', 'error', 'captcha'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'user', 'language'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 4,
                'maxLength' => 4,
                'offset' => 4
            ],
        ];
    }

    public function actionIndex()
    {
        $model = Article::findOne(['type' => Article::ARTICLE_TYPE_ANNOUNCE]);
        return $this->render('index', ['model' => $model]);
    }

    public function actionLogin()
    {
        $this->layout = 'loginLayout.php';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Updates User profile
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUser()
    {
        $user_id = Yii::$app->user->identity->id;
        $model = AdminUser::findOne($user_id);

        $model->scenario = 'update';
        $oldImage = $model->image;

        $auth = Yii::$app->authManager;
        $oldRole = ArrayHelper::getColumn($auth->getRolesByUser($model->id), 'name');
        $model->role = $oldRole;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {

                //upload the image
                $isOldImage = false;
                $res = Yii::$app->upload->UploadImage($model, 'image', 'uploads/avatar/');
                if ($res !== false) {
                    $model->image = $res;
                } else {
                    if (isset($oldImage) && !empty($oldImage)) {
                        $isOldImage = true;
                        $model->image = $oldImage;
                    }
                }

                if (isset($model->inputPassword)) {
                    $model->password = $model->inputPassword;
                }

                if ($model->save(false)) {
                    if (!$isOldImage) {
                        Yii::$app->upload->removeImage($oldImage);
                    }
                    return $this->redirect(['index']);
                }
            } else {
     
            }
        }

        return $this->render('user', [
            'model' => $model,
        ]);
    }


    public function actionLanguage()
    {
        $locale = Yii::$app->request->get('locale');
        if ($locale) {
            #use cookie to store language
            $l_cookie = new yii\web\Cookie(['name' => 'locale', 'value' => $locale, 'expire' => 3600 * 24 * 30,]);
            $l_cookie->expire = time() + 3600 * 24 * 30;
            Yii::$app->response->cookies->add($l_cookie);
        }

        $this->goBack(Yii::$app->request->headers['Referer']);
    }
}
