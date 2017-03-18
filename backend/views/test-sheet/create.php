<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TestSheet */

$this->title = Yii::t('backend', 'Create Test Sheet');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Test Sheets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-sheet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
