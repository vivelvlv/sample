<?php

namespace backend\controllers;

use Yii;
use yii\web\UploadedFile;
use common\models\SampleService;

use yii\web\NotFoundHttpException;

/**
 * Upload implements the upload opreation for files
 */

class UploadController extends BaseController
{

    public function actionSampleServiceDocument($id)
    {
        
        $service = SampleService::findOne($id);
        if($service == null)
        {
            throw new NotFoundHttpException('Sample Service does not exist.');
        }

        $output=[];
        $uploadPath = 'uploads/service';
        $fileName = 'sample_service_file';
        $saveFileName = 'SampleService-'.$service->id;
        $ret_file = Yii::$app->upload->UploadFile($fileName,$uploadPath,$saveFileName);

        if($ret_file !== false)
        {
            $service->document = $ret_file;
            if(!$service->save())
            {
                $output=['error'=>'Fail to process Sample Service model save'];
            }   
        }
       else
        {
            $output=['error'=>'Fail to SaveAs the file'];
        }
   
        echo \yii\helpers\Json::encode($output);
    }




   
}