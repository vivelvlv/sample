<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use backend\models\Code;

/* @var $this yii\web\View */
/* @var $model backend\models\Code */

$this->title = Yii::t('backend', 'Upload File');
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="upload">

  <?php
    echo FileInput::widget([
        'name' => 'sample_service_file',
        'options'=>[
            'multiple'=>false
        ],
        'pluginOptions' => [
            'uploadUrl' => Url::to(['upload/sample-service-document','id'=>$model->id]),
            'previewFileType' => 'any',
            'maxFileCount' => 1
        ]
    ]);
  ?>
</div>
