<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Sample */

$this->title = Yii::t('frontend', 'Update {modelClass}: ', [
    'modelClass' => 'Sample',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Samples'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<div class="sample-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
