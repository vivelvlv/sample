<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = $model->name;

$this->params['breadcrumbs'][] = Yii::t('backend', 'Update');
?>
<div class="service-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
