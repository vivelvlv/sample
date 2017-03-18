<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SampleService */

$this->title = Yii::t('backend', 'Create Sample Service');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Sample Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-service-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
