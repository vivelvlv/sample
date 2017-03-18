<?php

use common\models\SampleService;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\editable\Editable;

/* @var $this yii\web\View */

/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Complain List');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sample-service-index">

    <?php


    Modal::begin([
        'options' => [
            'tabindex' => false // important for Select2 to work properly
        ],
        'header' => '<h4>' . Yii::t('backend', 'Update') . ' </h4>',
        'id' => 'updateModal',
        'size' => 'modal-md',
    ]);
    echo "<div id='updateModalConent'></div>";
    Modal::end();

    //view
    Modal::begin([
        'header' => '<h4>' . Yii::t('backend', 'Complain List') . '</h4>',
        'id' => 'viewModal',
        'size' => 'modal-md',
        'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
    ]);
    echo "<div id='viewModalConent'></div>";
    Modal::end();


    $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'order' => DynaGrid::ORDER_FIX_LEFT
        ],
        [
            'attribute' => 'created_at',
            'value' => 'created_at',
            'format' => 'date',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions' => ([
                'presetDropdown' => TRUE,
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                    'opens' => 'left'
                ]
            ]),
            'hAlign' => 'left',
            'vAlign' => 'middle',
        ],
        'title',
        [
            'attribute' => 'status',
            'value' => 'statusText'
        ],

        'content',
        'feedback',
        [
            'attribute' => 'feedback_at',
            'value' => 'feedback_at',
            'format' => 'date',
            'filterType' => GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions' => ([
                'presetDropdown' => TRUE,
                'convertFormat' => true,
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                    'opens' => 'left'
                ]
            ]),
            'hAlign' => 'left',
            'vAlign' => 'middle',
        ],
        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{update-complain} &nbsp;&nbsp;&nbsp;&nbsp;{download}&nbsp;&nbsp;&nbsp;&nbsp;{complainlist}',
            'buttons' => [
                'update-complain' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('backend', 'Update'),
                        'aria-label' => Yii::t('backend', 'Update'),
                        'name' => 'modalUpdate'
                    ];
                    return Html::a('<span class="glyphicon glyphicon-list"></span>', $url, $options);
                }
            ],
            'vAlign' => 'middle',
            'width' => '140px',
            'order' => DynaGrid::ORDER_FIX_RIGHT
        ],
    ];

    echo DynaGrid::widget([
        'columns' => $gridColumns,
        'theme' => 'panel-info',
        'showPersonalize' => true,
        'storage' => 'cookie',
        //'allowPageSetting'=>false,
        'allowThemeSetting' => false,
        'allowFilterSetting' => false,
        'allowSortSetting' => false,
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'showPageSummary' => true,
            'bordered' => true,
            'striped' => true,
            'hover' => true,
            'pjax' => true,
            'panel' => [
                'heading' => '<h3 class="panel-title"><i class="fa fa-flask"></i>  ' . Yii::t('backend', 'Service') . '</h3>',
                'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('backend', 'Show All  Services') . '</em></div>',
            ],
            'toolbar' => [
                ['content' => '{dynagrid}'],
            ],
            'options' => ['id' => 'my_test_grid']
        ],
        'options' => ['id' => 'my_test_dynagrid'] // a unique identifier is important
    ]);

    ?>

</div>
<?php
$script = <<< JS

$('#my_test_dynagrid').on('click','a[name="modalUpdate"]',function(e){
        $('#updateModal').modal('show')
                   .find('#updateModalConent')
                   .load($(this).attr('href'));
        return false;
});

$('#updateModal').on('hide.bs.modal',function(e){
        $.pjax.reload({container:'#my_test_grid'});
});

JS;
$this->registerJs($script);
?>
