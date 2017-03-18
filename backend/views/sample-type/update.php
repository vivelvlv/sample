<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SampleType */

$this->title = Yii::t('backend', 'Update Sample Type') . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Sample Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="sample-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
