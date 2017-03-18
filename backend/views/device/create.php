<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Device */

$this->title = Yii::t('backend', 'Create Device');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
