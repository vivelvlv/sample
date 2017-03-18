<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SampleService */

$this->title = $model->id;

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="sample-service-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
