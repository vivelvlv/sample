<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TestSheet */

$this->title = Yii::t('frontend', 'Create Test Sheet');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-sheet-create">

    <?= $this->render('_form', [
        'model' => $model,
        'userInfo' => $userInfo,
        'modelSamples' => $modelSamples
    ]) ?>

</div>
