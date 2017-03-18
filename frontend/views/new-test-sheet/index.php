<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use common\models\TestSheet;

/* @var $this yii\web\View */

/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('frontend', 'Test Sheets');
$this->params['breadcrumbs'][] = $this->title;
?>

<br>
<div class="test-sheet-index">
    <?php
    //update
    Modal::begin([
        'header' => '<h4>' . Yii::t('frontend', 'Test Sheet') . ' </h4>',
        'id' => 'updateModal',
        'size' => 'modal-md',
    ]);
    echo "<div id='updateModalConent'></div>";
    Modal::end();

    //view
    Modal::begin([
        'header' => '<h4>' . Yii::t('frontend', 'Test Sheet') . ' </h4>',
        'id' => 'viewModal',
        'size' => 'modal-md',
    ]);
    echo "<div id='viewModalConent'></div>";
    Modal::end();

    ?>
    <?php
    $gridColumns = [

        [
            'class' => 'kartik\grid\CheckboxColumn',
            'headerOptions' => ['class' => 'kartik-sheet-style'],
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
        [
            'attribute' => 'name',
            'vAlign' => 'middle',
        ],
        [
            'attribute' => 'storage_condition',
            'vAlign' => 'middle',
            'value' => 'storageConditionText',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => TestSheet::storageConditionItems(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
            'visible' => false,
        ],
        [
            'attribute' => 'service_type',
            'vAlign' => 'middle',
            'value' => 'serviceTypeText',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => TestSheet::serviceTypeItems(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
            'visible' => false,
        ],
        [
            'attribute' => 'report_fetch_type',
            'vAlign' => 'middle',
            'value' => 'fetchReportTypeText',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => TestSheet::fetchReportTypeItems(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
            'visible' => false,
        ],
        [
            'attribute' => 'sample_handle_type',
            'vAlign' => 'middle',
            'value' => 'sampleHandleTypeText',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => TestSheet::sampleHandleTypeItems(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
            'visible' => false,
        ],
        [
            'attribute' => 'status',
            'value' => 'statusText',
            'hAlign' => 'left',
            'vAlign' => 'middle',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => TestSheet::statusItems(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
        ],
        [
            'attribute' => 'comment',
            'vAlign' => 'middle',
        ],

        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{update-sheet-samples} &nbsp;&nbsp;{view} &nbsp;&nbsp;{update}',
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
                'update' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Update'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                        'name' => 'modalUpdate'
                    ];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
            ],
            'visibleButtons' => [
                'update' => function ($model, $key, $index) {
                    return $model->status != TestSheet::TESTSHEET_STATUS_RECEIVE ? true : false;
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
                'heading' => '<h3 class="panel-title"><i class="fa fa-files-o"></i>  ' . Yii::t('frontend', 'Test Sheets') . '</h3>',
                'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('frontend', 'Show All Test Sheets') . '</em></div>',
                'after' => Html::button(Yii::t('frontend', 'Print'), ['class' => 'btn btn-success', 'id' => 'check-print-multiple'])


            ],
            'toolbar' => [
                ['content' => Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['class' => 'btn btn-success', 'data-pjax' => '0'])


                ],
                ['content' => '{dynagrid}'],
            ],

        ],
        'options' => ['id' => 'test_sheet_grid'] // a unique identifier is important
    ]);

    ?>

</div>

<?php

$urlPrintMultiple = Url::to(['print-list']);

$urlPrintMultipleItemPage = Url::to(['print']);

$script = <<< JS
var updateModal =  $('#updateModal');

$('#updateModal').on('kbModalSubmit', function(event, data, status, xhr) 
{
    updateModal.modal('toggle')
    $.pjax.reload({container:'#test_sheet_grid-pjax'});
});

$('#test_sheet_grid-pjax').on('click','a[name="modalView"]',function(e){
        $('#viewModal').modal('show')
                   .find('#viewModalConent')
                   .load($(this).attr('href'));
        return false;
});


$('#test_sheet_grid-pjax').on('click','#createSampleButton',function(e){
        $('#createModel').modal('show')
                   .find('#createModalConent')
                   .load($(this).attr('value'));
        return false;
});



  $("#test_sheet_grid-pjax").on('click','#check-print-multiple',function(e) {
      
    var resultRows = [];
    $("input:checkbox[name='selection[]']:checked").each(function() { // 遍历name=test的多选框
        resultRows.push($(this).val());
    });
    
    if(resultRows.length > 0){
    var id = resultRows.pop();        
    }
    
    if(typeof id == "undefined"){
        alert("请选择要打印的表单");
        return;
    }
    
    window.open("{$urlPrintMultipleItemPage}"+"&id="+id+"&extRows="+resultRows);
    
//    $.post(
//        "{$urlPrintMultiple}",
//        {
//            pk:resultRows
//        },
//        function(params) {
//          alert(params);
//        }
//    );
    
  });

JS;
$this->registerJs($script);
?>
