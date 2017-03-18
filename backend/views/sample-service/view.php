<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SampleService */

$this->title = $model->id;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sample-service-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'sample_id',
                'value' => $model->sample->name
            ],
            [
                'attribute' => 'service_id',
                'value' => $model->service->name
            ],
            'document',
            [
                'attribute' => 'status',
                'value' => $model->getStatusText(),
            ],
            'created_at:date',
            [
                'attribute' => 'completed_at',
                'value' => $model->completed_at > 24 * 60 * 60 ? Yii::$app->formatter->asDate($model->completed_at)
                    : Yii::t('yii', '(not set)'),
            ]
        ],
    ]) ?>

</div>
