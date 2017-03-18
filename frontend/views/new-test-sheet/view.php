<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TestSheet */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-sheet-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            [
                'attribute'=>'storage_condition',
                'value'=>$model->getStorageConditionText()
            ],
            [
                'attribute'=>'service_type',
                'value'=>$model->getServiceTypeText()
            ],
            [
                'attribute'=>'report_fetch_type',
                'value'=>$model->getFetchReportTypeText()
            ],
            [
                'attribute'=>'sample_handle_type',
                'value'=>$model->getSampleHandleTypeText()
            ],
            [
                'attribute'=>'status',
                'value'=>$model->getStatusText()
            ],
            'created_at:date',
            [
                'attribute'=> 'completed_at',
                'value'=> $model->completed_at > 24*60*60 ? Yii::$app->formatter->asDate($model->completed_at) :Yii::t('yii','(not set)'),
            ],
            'comment',
        ],
    ]) ?>

</div>
