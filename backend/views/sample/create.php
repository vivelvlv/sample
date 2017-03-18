<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Sample */

$this->title = Yii::t('backend', 'Create Sample');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Samples'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
