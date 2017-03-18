<?php
/**
 * Created by PhpStorm.
 * User: vive
 * Date: 2017/1/20
 * Time: 下午1:58
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SampleService */

$this->title = "申诉已提交";
$this->params['breadcrumbs'][] = ['label' => Yii::t('frontend', 'Sample Services'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-service-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'title',
                'value' => $model->title
            ],
            [
                'attribute' => 'content',
                'value' => $model->content
            ]
        ],
    ]) ?>

</div>