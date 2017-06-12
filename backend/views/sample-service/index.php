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

$this->title = Yii::t('backend', 'Sample Service');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="sample-service-index">

        <?php

        //view
        Modal::begin([
            'header' => '<h4>' . Yii::t('backend', 'Sample') . ' </h4>',
            'id' => 'viewModal',
            'size' => 'modal-md',
            'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
        ]);
        echo "<div id='viewModalConent'></div>";
        Modal::end();


        //complain-list
        // Modal::begin([
        //     'header' => '<h4>' . Yii::t('backend', 'Complain List (history)') . ' </h4>',
        //     'id' => 'complainListModal',
        //     'size' => 'modal-md',
        //     'footer' => '<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
        // ]);
        // echo "<div id='complainListModalConent'></div>";
        // Modal::end();


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
//            [
//                'label' => Yii::t('backend', 'Sample Code'),
//                'value' => 'sample.serial_number',
//                'vAlign' => 'middle',
//                'width' => '10%',
//            ],
            [
                'attribute' => 'service_id',
                'vAlign' => 'middle',
                'value' => 'service.name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => Service::serviceItems(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => ' '],
                'format' => 'raw',
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
                            'title' => Yii::t('backend', 'Download File'),
                            'aria-label' => Yii::t('backend', 'Download File'),
                            'data-pjax' => '0',
                            'target' => '_blank'
                        ];

                        $url = Yii::$app->upload->fileRootUrl . $model->document;
                        return Html::a(Yii::t('backend', 'Download File'), $url, $options);
                    } else {
                        return '';
                    }
                },
                'width' => '20%',
                'format' => 'raw'
            ],

            [
                'attribute' => 'user_id',
                'vAlign' => 'middle',
                'width' => '15%',
                'value' => 'user.user_name',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => User::userAttributeLabel(),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => ' '],
                'format' => 'raw',
            ],

            [
                'attribute' => 'sample_comment',
                'value' => 'sample.comment',
                'label' => Yii::t('backend', "Sample Comment")
            ],

            ['class' => 'kartik\grid\ActionColumn',
                'template' => '{view} &nbsp;&nbsp;&nbsp;{complainlist}',
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
                    'complainlist' => function ($model, $key, $index) {
                        return isset($model->document) && strlen($model->document) > 0
                        || ($model->status == SampleService::SAMPLESERVICE_STATUS_COMPLETE || $model->status == SampleService::SAMPLESERVICE_STATUS_IN_COMPLAINT)
                        && $model->complainNumbers > 0 ? true : false;
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
                'rowOptions' => function ($model) {
                    if ($model->status == SampleService::SAMPLESERVICE_STATUS_IN_TEST) {
                        if ($model->received_at > 24 * 60 * 60 && $model->received_at - time() > 48 * 60 * 60)
                            return ['class' => GridView::TYPE_DANGER];
                    }
                },
                'showPageSummary' => true,
                'bordered' => true,
                'striped' => true,
                'hover' => true,
                'pjax' => true,
                'panel' => [
                    'heading' => '<h3 class="panel-title"><i class="fa fa-flask"></i>  ' . Yii::t('backend', 'Service') . '</h3>',
                    'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('backend', 'Show All  Services') . '</em></div>',
                    'after' => Html::button(Yii::t('backend', 'Print All'), ['class' => 'btn btn-success pull-right', 'id' => 'print-all'])
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

$urlPrintAll = Url::to(['sample-service/print-all']);
$script = <<< JS

$('#sample_service_index_grid_dynagrid-pjax').on('click','a[name="modalView"]',function(e){
        $('#viewModal').modal('show')
                   .find('#viewModalConent')
                   .load($(this).attr('href'));
        return false;
});

$('#sample_service_index_grid_dynagrid-pjax').on('click','#print-all',function(e){
       var url = "{$urlPrintAll}";
       var content = $("#sampleservicesearch-created_at").val();
       content = encodeURIComponent(content);
       window.location.href = url+"&regin="+content;
});






// 显示申诉列表
// $('#sample_service_index_grid_dynagrid-pjax').on('click','a[name="modalComplainlist"]',function(e){
//         $('#complainListModal').modal('show')
//                    .find('#complainListModalConent')
//                    .load($(this).attr('href'));
//         return false;
// });

JS;
$this->registerJs($script);
?>