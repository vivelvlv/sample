<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Sample */

$this->title = Yii::t('frontend', 'Create Sample');
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Samples'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
