<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AuthItem */

$this->title = Yii::t('backend', 'Create Role');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
