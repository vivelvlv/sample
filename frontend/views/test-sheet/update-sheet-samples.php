<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SampleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('frontend', 'Update Test Sheet');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="update-service-index">
    
    <?= $this->render('_sheet_samples', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sheet_id'=>$sheet_id,
            'is_new'=>$is_new
       ]) ?>
</div>