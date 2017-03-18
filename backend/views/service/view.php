<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'catalog_number',
            'name',
            'description',
            'price',
            'created_at',
            [
                'attribute' => 'device_id',
                'format' => 'raw',
                'value' => isset($model->device) ? $model->device->name : Yii::t('yii', '(not set)'),
            ],
            'comment',
            'is_show:boolean',
        ],
    ]) ?>

</div>
