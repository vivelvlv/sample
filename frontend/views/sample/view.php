<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Sample */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Samples'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-view">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'serial_number',
            [
                'attribute' => 'test_sheet_id',
                'value' => $model->testSheet->name
            ],
            [
                'attribute' => 'weight',
                'value' => $model->weight . ' ' . $model->sampleUnit->name
            ],
            [
                'attribute' => 'status',
                'value' => $model->getStatusText()
            ],
            'created_at:date',
            [
                'attribute' => 'completed_at',
                'value' => $model->completed_at > 24 * 60 * 60 ? Yii::$app->formatter->asDate($model->completed_at) : Yii::t('yii', '(not set)'),
            ],
            'comment',
        ],
    ]) ?>

</div>
