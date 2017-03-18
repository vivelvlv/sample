<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SampleUnit */

$this->title = Yii::t('backend', 'Create Sample Unit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Sample Unit'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-unit-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
