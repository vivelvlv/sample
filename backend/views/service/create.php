<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = Yii::t('backend', 'Create Service');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
