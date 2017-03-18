<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SampleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



$this->title = Yii::t('frontend', 'Create Test Sheet');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="add-service-index">
    <legend class="text-info"><small> <?= Yii::t('frontend','Second Step: Add Samples And Sample Sevices') ?></small></legend>
    <?= $this->render('_sheet_samples', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'sheet_id'=>$sheet_id,
            'is_new'=>$is_new
       ]) ?>
</div>