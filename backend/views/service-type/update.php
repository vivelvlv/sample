<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceType */

$this->title =  $model->name;

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="service-type-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
