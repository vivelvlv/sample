<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\models\Sample;
use common\models\SampleType;

$this->title = Yii::t('frontend', 'Samples');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

//create
Modal::begin([
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'header' => '<h4>' . Yii::t('frontend', 'Sample Service') . ' </h4>',
    'id' => 'createModel',
    'size' => 'modal-md',
]);
echo "<div id='createModalConent'></div>";
Modal::end();


//update
Modal::begin([
    'header' => '<h4>' . Yii::t('frontend', 'Sample') . ' </h4>',
    'id' => 'updateModal',
    'size' => 'modal-md',
]);
echo "<div id='updateModalConent'></div>";
Modal::end();

//view
Modal::begin([
    'header' => '<h4>' . Yii::t('frontend', 'Sample') . ' </h4>',
    'id' => 'viewModal',
    'size' => 'modal-md',
    'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
]);
echo "<div id='viewModalConent'></div>";
Modal::end();

$gridColumns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {

            if (!empty($model->sampleServices)) {
                return GridView::ROW_COLLAPSED;
            } else {
                return '';
            }
        },
        'detailUrl' => Url::to(['sample-service/index']),
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true,
    ],
    'name',
    'serial_number',
    'project_sn',
    [
        'attribute' => 'weight',
        'value' => function ($model, $key, $index, $column) {
            return $model->weight;
        },
        'hAlign' => 'left',
        'vAlign' => 'middle',
    ],
    [
        'attribute' => 'status',
        'value' => 'statusText',
        'hAlign' => 'left',
        'vAlign' => 'middle',
        'filterType' => GridView::FILTER_SELECT2,
        'filter' => Sample::statusItems(),
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
        ],
        'filterInputOptions' => ['placeholder' => ' '],
        'format' => 'raw',
    ],
    'comment',
    ['class' => 'kartik\grid\ActionColumn',
        'template' => '{view}',
        'buttons' => [
            'view' => function ($url, $model, $key) {
                $options = [
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'name' => 'modalView',
                ];
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
            },
            // 'update' => function ($url, $model, $key) {
            //     $options = [
            //         'title' => Yii::t('yii', 'Update'),
            //         'aria-label' => Yii::t('yii', 'Update'),
            //         'data-pjax' => '0',
            //         'name' => 'modalUpdate'
            //     ];
            //     return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
            // }
        ],
        'visibleButtons' => [
            //'create-sub-project'=>Yii::$app->user->identity->isOwn('sub-project/create'),
            //'update'=>Yii::$app->user->identity->isOwn('project/update'),
        ],
        'width' => '140',
    ],
];
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $gridColumns,
    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
    'filterRowOptions' => ['class' => 'kartik-sheet-style'],
    'pjax' => true, // pjax is set to always true for this demo
    // set your toolbar
    'toolbar' => [
        '{export}',
        '{toggleData}'
    ],
    'export' => [
        'fontAwesome' => true,
        'showConfirmAlert' => false,
        'target' => GridView::TARGET_BLANK
    ],
    'exportConfig' => [
        GridView::CSV => ['filename' => Yii::t('frontend', 'Samples')],
        GridView::HTML => ['filename' => Yii::t('frontend', 'Samples')],
        GridView::EXCEL => ['filename' => Yii::t('frontend', 'Samples')],
    ],
    'bordered' => true,
    'striped' => true,
    'hover' => true,
    'panel' => [

        //'heading'=>$heading,
    ],

    'panel' => [
        'type' => GridView::TYPE_INFO,
        'heading' => '<h3 class="panel-title"><i class="fa fa-calendar"></i>  ' . Yii::t('frontend', 'Samples') . '</h3>',
        'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('frontend', 'Show All Samples') . '</em></div>',
    ],
    'options' => ['id' => 'sample_index_grid']
]);
?>


    </div>
<?php
$script = <<< JS
var createModel =  $('#createModel');
$('#sample_index_grid-pjax').on('click','a[name="modalSampleServiceProject"]',function(e){
       var url = $(this).attr('href');

        createModel.kbModalAjax({
            url: url,
            ajaxSubmit: 'true',
        });
       
        createModel.modal('show')
                   .find('.modal-body')
                   .load(url); 

        return false;
});

$('#createModel').on('kbModalSubmit', function(event, data, status, xhr) 
{
    createModel.modal('toggle')
    $.pjax.reload({container:'#sample_index_grid-pjax'});
});

$('#sample_index_grid-pjax').on('click','a[name="modalUpdate"]',function(e){
        $('#updateModal').modal('show')
                   .find('#updateModalConent')
                   .load($(this).attr('href'));
        return false;
});

$('#sample_index_grid-pjax').on('click','a[name="modalView"]',function(e){
        $('#viewModal').modal('show')
                   .find('#viewModalConent')
                   .load($(this).attr('href'));
        return false;
});

JS;
$this->registerJs($script);
?>