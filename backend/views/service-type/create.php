<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ServiceType */

$this->title = Yii::t('backend', 'Create Service Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Service Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
