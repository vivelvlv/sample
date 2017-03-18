<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TestSheet */

$this->title = Yii::t('frontend', 'Update') . ":" . $model->name;
$this->params['breadcrumbs'][] = Yii::t('frontend', 'Update');
?>
<div class="test-sheet-update">
    <?= $this->render('_form', [
        'model' => $model,
        'userInfo' => $userInfo,
        'modelSamples' => $modelSamples
    ]) ?>

</div>
