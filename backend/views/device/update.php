<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Device */

$this->title =  $model->name;

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="device-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
