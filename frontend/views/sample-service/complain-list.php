<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\editable\Editable;

/* @var $this yii\web\View */

/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('frontend', 'Complain List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-service-index">
    <?php

    //view
    Modal::begin([
        'header' => '<h4>' . Yii::t('frontend', 'Complain List') . '</h4>',
        'id' => 'viewModal',
        'size' => 'modal-lg',
        'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
    ]);
    echo "<div id='viewModalConent'></div>";
    Modal::end();

    $gridColumns = [
        ['class' => 'yii\grid\SerialColumn'],
        'title',
        [
            'attribute' => 'status',
            'value' => 'statusText'
        ],
        'content',
        'created_at:date',
        'feedback',
        'feedback_at:date',
    ];
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel'=>$searchModel,
        'columns' => $gridColumns,
        'headerRowOptions' => ['class' => GridView::TYPE_INFO],
        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
        'rowOptions' => ['class' => GridView::TYPE_DANGER],
        'pjax' => true, // pjax is set to always true for this demo
        // set your toolbar
        'toolbar' => [
            ['content' => ''],
        ],
        'bordered' => true,
        'striped' => true,
        'hover' => true,
        'panel' => [
            'type' => GridView::TYPE_INFO,
            //'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-subtitles"></i>  ' . Yii::t('frontend', 'Sample Service') . '</h3>',
            'footer' => false,
            'beforeOptions' => ['class' => GridView::TYPE_DANGER],
            'afterOptions' => ['class' => GridView::TYPE_DANGER],
        ],
        'options' => ['id' => 'sample_service_index_grid']
    ]);
    ?>

</div>

