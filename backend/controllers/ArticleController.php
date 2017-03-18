<?php

namespace backend\controllers;

use Yii;
use common\models\Article;
use common\models\ArticleSearch;
use backend\models\PushMessage;
use backend\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use backend\controllers\BaseController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
class ArticleController extends BaseController
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

    public function actionTerms()
    {
        $model = Article::findOne(['type' => Article::ARTICLE_TYPE_TERM]);
        if ($model == null) {
            $model = new Article();
            $model->title = Yii::t('backend', 'Terms');
            $model->is_show = 1;
            $model->type = Article::ARTICLE_TYPE_TERM;
            $model->save();
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {

            }
        }

        return $this->render('terms', [
            'model' => $model,
        ]);
    }

    public function actionAnnounce()
    {
        $model = Article::findOne(['type' => Article::ARTICLE_TYPE_ANNOUNCE]);
        if ($model == null) {
            $model = new Article();
            $model->title = Yii::t('backend', 'Announce');
            $model->is_show = 1;
            $model->type = Article::ARTICLE_TYPE_ANNOUNCE;
            $model->save();
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {

            }
        }

        return $this->render('announce', [
            'model' => $model,
        ]);
    }

    protected function findModel($type)
    {
        if (($model = Article::findOne(['type' => $type])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionUpload()
    {
        $uploadedFile = UploadedFile::getInstanceByName('upload');
        $mime = \yii\helpers\FileHelper::getMimeType($uploadedFile->tempName);
        $file = time() . "_" . $uploadedFile->name;

        $dir = dirname(Yii::$app->homeUrl);
        if ($dir == "/") {
            $dir = "";
        }
        //$url =Yii::$app->request->hostInfo.$dir.'/uploads/ckeditor/'.$file;
        $url = $dir . '/uploads/ckeditor/' . $file;
        $uploadPath = Yii::getAlias('@webroot') . '/uploads/ckeditor/' . $file;

        //extensive suitability check before doing anything with the file…
        if ($uploadedFile == null) {
            $message = "No file uploaded.";
        } else if ($uploadedFile->size == 0) {
            $message = "The file is of zero length.";
        } else if ($mime != "image/jpeg" && $mime != "image/png") {
            $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
        } else if ($uploadedFile->tempName == null) {
            $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
        } else {

            $message = "";
            $move = $uploadedFile->saveAs($uploadPath);
            if (!$move) {
                $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
            }
        }

        $funcNum = $_GET['CKEditorFuncNum'];
        echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
    }

}
