<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SampleType */

$this->title = Yii::t('backend', 'Create Sample Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Sample Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
