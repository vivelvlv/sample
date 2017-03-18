<?php

use common\models\SampleService;
use common\models\Service;
use common\models\User;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use common\models\Sample;
use common\models\SampleType;

$this->title = Yii::t('frontend', 'Sample Service');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="sample-service-index">

        <?php

        //view
        Modal::begin([
            'header' => '<h4>' . Yii::t('frontend', 'Sample') . ' </h4>',
            'id' => 'viewModal',
            'size' => 'modal-md',
            'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
        ]);
        echo "<div id='viewModalConent'></div>";
        Modal::end();

        //complain
        Modal::begin([
            'options' => [
                'tabindex' => false // important for Select2 to work properly
            ],
            'header' => '<h4>' . Yii::t('frontend', 'Complain') . ' </h4>',
            'id' => 'complainModal',
            'size' => 'modal-md',
        ]);
        echo "<div id='complainModalConent'></div>";
        Modal::end();

        //complain-list
        Modal::begin([
            'header' => '<h4>' . Yii::t('frontend', 'Complain List (history)') . ' </h4>',
            'id' => 'complainListModal',
            'size' => 'modal-md',
            'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
        ]);
        echo "<div id='complainListModalConent'></div>";
        Modal::end();


        $gridColumns = [
            [
                'class' => 'kartik\grid\SerialColumn',
                'headerOptions' => ['class' => 'kartik-sheet-style',
                    'order' => DynaGrid::ORDER_FIX_LEFT]
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
                'attribute' => 'sample_id',
                'value' => 'sample.name',
                'visible' => true
            ],
            [
                'label' => Yii::t('frontend', 'Project Sn'),
                'value' => 'sample.project_sn',
                'visible' => false
            ],
            [
                'attribute' => 'service_id',
                'value' => 'service.name'
            ],
            [
                'attribute' => 'status',
                'value' => 'statusText',
                'hAlign' => 'left',
                'vAlign' => 'middle',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => SampleService::statusItems(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => ' '],
                'format' => 'raw',
                'width' => '15%',
            ],
            [
                'attribute' => 'document',
                'value' => function ($model, $key, $index, $widget) {
                    if (isset($model->document)) {
                        $options = [
                            'title' => Yii::t('frontend', 'Download File'),
                            'aria-label' => Yii::t('frontend', 'Download File'),
                            'data-pjax' => '0',
                            'target' => '_blank'
                        ];

                        $url = Yii::$app->upload->fileRootUrl . $model->document;
                        return Html::a(Yii::t('frontend', 'Download File'), $url, $options);
                    } else {
                        return '';
                    }
                },
                'width' => '20%',
                'format' => 'raw'
            ],

            ['class' => 'kartik\grid\ActionColumn',
                'template' => '{view} &nbsp;&nbsp;{complain}&nbsp;&nbsp;{complainlist}',
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
                    'complain' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Complain'),
                            'aria-label' => Yii::t('yii', 'Complain'),
                            //'data-pjax' => '0',
                            'data-pjax' => 'sample_service_index_grid_dynagrid-pjax',
                            'name' => 'modalComplain'
                        ];
                        return Html::a('<span class="glyphicon glyphicon-console"></span>', $url, $options);
                    },
                    'complainlist' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Complain List'),
                            'aria-label' => Yii::t('yii', 'Complain List'),
                            'data-pjax' => '0',
                            'name' => 'modalComplainlist'
                        ];
                        return Html::a('<span class="glyphicon glyphicon-list"></span>', $url, $options);
                    }
                ],
                'visibleButtons' => [
                    'view' => function ($model, $key, $index) {
                        return false;
                    },
                    'complain' => function ($model, $key, $index) {
                        return isset($model->document) && strlen($model->document) > 0
                        || ($model->status == \common\models\SampleService::SAMPLESERVICE_STATUS_COMPLETE
                            || $model->status == \common\models\SampleService::SAMPLESERVICE_STATUS_IN_COMPLAINT) ? true : false;
                    },

                    'complainlist' => function ($model, $key, $index) {
                        return isset($model->document) && strlen($model->document) > 0
                        || ($model->status == \common\models\SampleService::SAMPLESERVICE_STATUS_COMPLETE
                            || $model->status == \common\models\SampleService::SAMPLESERVICE_STATUS_IN_COMPLAINT) ? true : false;
                    }
                ],
                'width' => '140',
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
                    '{toggleData}',
                    '{export}',
                ],
                'export' => [
                    'fontAwesome' => true,
                    'showConfirmAlert' => false,
                    'target' => GridView::TARGET_BLANK
                ],
                'exportConfig' => [
                    GridView::CSV => ['filename' => Yii::t('app', 'Sample Service')],
                    GridView::HTML => ['filename' => Yii::t('app', 'Sample Service')],
                    GridView::EXCEL => ['filename' => Yii::t('app', 'Sample Service')],
                ],
                'options' => ['id' => 'sample_service_index_grid']
            ],
            'options' => ['id' => 'sample_service_index_grid_dynagrid'] // a unique identifier is important
        ]);


        ?>

    </div>

<?php
$script = <<< JS

$('#sample_service_index_grid_dynagrid-pjax').on('click','a[name="modalView"]',function(e){
        $('#viewModal').modal('show')
                   .find('#viewModalConent')
                   .load($(this).attr('href'));
        return false;
});

// 申诉
// $('#sample_service_index_grid_dynagrid-pjax').on('click','a[name="modalComplain"]',function(e){
//         $('#complainModal').modal('show')
//                    .find('#complainModalConent')
//                    .load($(this).attr('href'));
//         return false;
// });

var complainModal =  $('#complainModal');
$('#sample_service_index_grid_dynagrid-pjax').on('click','a[name="modalComplain"]',function(e){
       var url = $(this).attr('href');

        complainModal.kbModalAjax({
            url: url,
            ajaxSubmit: 'true',
        });
       
        complainModal.modal('show')
                   .find('.modal-body')
                   .load(url); 

        return false;
});

$('#complainModal').on('kbModalSubmit', function(event, data, status, xhr) 
{
    complainModal.modal('toggle')
    $.pjax.reload({container:'#sample_service_index_grid_dynagrid-pjax'});
});

// 显示申诉列表
$('#sample_service_index_grid_dynagrid-pjax').on('click','a[name="modalComplainlist"]',function(e){
        $('#complainListModal').modal('show')
                   .find('#complainListModalConent')
                   .load($(this).attr('href'));
        return false;
});

JS;
$this->registerJs($script);
?>