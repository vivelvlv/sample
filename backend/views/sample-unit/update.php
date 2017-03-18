<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SampleUnit */

$this->title = Yii::t('backend', 'Update Sample Unit') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Sample Unit'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="sample-unit-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
