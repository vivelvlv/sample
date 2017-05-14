<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

use common\models\Service;
use common\models\User;
use common\models\SampleService;
use backend\models\SampleServiceMyTestSearch;

use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('backend', 'My Test');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php


Modal::begin([
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],
    'header' => '<h4>' . Yii::t('backend', 'Sample Service') . ' </h4>',
    'id' => 'uploadModal',
    'size' => 'modal-md',
    'footer' => '<button type="button" id="closeUploadModal" class="btn btn-danger" data-dismiss="modal">Close</button>'
]);
echo "<div id='uploadModalConent'></div>";
Modal::end();
?>

<?php echo $this->render('/sample-service/_tab_view'); ?>

<br>
<br>
<br>
<div class="in-library-index">

    <?php
    $gridColumns = [
        [
            'class' => 'kartik\grid\CheckboxColumn',
            'rowSelectedClass' => GridView::TYPE_WARNING,
            'headerOptions' => ['class' => 'kartik-sheet-style',
                'order' => DynaGrid::ORDER_FIX_LEFT]
        ],
        [
            'attribute' => 'received_at',
            'value' => 'received_at',
            'format' => 'datetime',
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
            'value' => function ($model, $key, $index, $widget) {
                $sample = $model->getSample()->one();
                if (isset($sample->document) && strlen($sample->document) > 1) {
                    $options = [
                        'title' => Yii::t('backend', 'Download File'),
                        'aria-label' => Yii::t('backend', 'Download File'),
                        'data-pjax' => '0',
                        'target' => '_blank'
                    ];

                    $url = Yii::$app->upload->fileRootUrl . $sample->document;
                    return Html::a($sample->name, $url, $options);
                } else {
                    return $sample->name;
                }
            },
            'width' => '10%',
            'format' => 'raw',
            'visible' => true,
            'vAlign' => 'middle',
        ],
//        [
//            'label' => Yii::t('backend', 'Sample Code'),
//            'value' => 'sample.serial_number',
//            'vAlign' => 'middle',
//            'width' => '10%',
//        ],
        [
            'attribute' => 'service_id',
            'vAlign' => 'middle',
            'value' => 'service.name',
            'width' => '15%',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => Service::serviceItems(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
        ],
        [
            'label' => Yii::t('backend', 'Sample Type'),
            'value' => 'sample.sampleType.name',
            'vAlign' => 'middle',
            'width' => '10%',
        ],

        [
            'label' => Yii::t('backend', 'Barcode'),
            'value' => 'barcode',
            'vAlign' => 'middle',
            'width' => '10%',
        ],
        [
            'label' => Yii::t('backend', 'Weight'),
            'value' => function ($model, $key, $index, $column) {
                if (isset($model) && isset($model->sample) && isset($model->sample->weight)) {
                    return $model->sample->weight;
                } else {
                    return 0;
                }
            },
            'vAlign' => 'middle',
            'width' => '8%',
        ],
        [
            'attribute' => 'status',
            'value' => 'statusText',
            'hAlign' => 'left',
            'vAlign' => 'middle',
            'filterType' => GridView::FILTER_SELECT2,
            'filter' => SampleServiceMyTestSearch::statusListItems(),
            'filterWidgetOptions' => [
                'pluginOptions' => ['allowClear' => true],
            ],
            'filterInputOptions' => ['placeholder' => ' '],
            'format' => 'raw',
            'width' => '10%',
        ],
        [
            'attribute' => 'user_id',
            'vAlign' => 'middle',
            'width' => '10%',
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
            'attribute' => Yii::t('backend', 'Sample Comment'),
            'value' => 'sample.comment',
            'vAlign' => 'middle',
            'width' => '20%',
            'visible' => false

        ],
        ['class' => 'kartik\grid\ActionColumn',
            'template' => '{upload} &nbsp;&nbsp;&nbsp;&nbsp;{download}&nbsp;&nbsp;&nbsp;&nbsp;{complainlist}',
            'buttons' => [
                'upload' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('backend', 'Upload File'),
                        'aria-label' => Yii::t('backend', 'Upload File'),
                        'name' => 'modalUpload'
                    ];
                    return Html::a('<span class="fa  fa-upload"></span>', $url, $options);
                },

                'download' => function ($url, $model, $key) {

                    $options = [
                        'title' => Yii::t('yii', 'Download File'),
                        'aria-label' => Yii::t('yii', 'Download File'),
                        'data-pjax' => '0',
                        'target' => '_blank'
                    ];

                    $url = Yii::$app->upload->fileRootUrl . $model->document;
                    return Html::a('<span class="glyphicon glyphicon-download"></span>', $url, $options);
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
                'upload' => function ($model, $key, $index) {
                    return $model->status == SampleService::SAMPLESERVICE_STATUS_COMPLETE || $model->status == SampleService::SAMPLESERVICE_STATUS_IN_COMPLAINT;
                },
                'download' => function ($model, $key, $index) {
                    return !empty($model->document);
                },
                'complainlist' => function ($model, $key, $index) {
                    return ($model->status == SampleService::SAMPLESERVICE_STATUS_COMPLETE || $model->status == SampleService::SAMPLESERVICE_STATUS_IN_COMPLAINT)
                    && $model->complainNumbers > 0;
                }
            ],
            'vAlign' => 'middle',
            'width' => '100px',
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
                'after' => Html::button(Yii::t('backend', 'Multiple Complete'), ['class' => 'btn btn-success', 'id' => 'complete-pass-multiple'])
                    .
                    Html::button(Yii::t('backend', 'Multiple Normal Back'), ['class' => 'btn btn-danger pull-right', 'id' => 'normal-back-multiple'])
                    .
                    Html::button(Yii::t('backend', 'Multiple Exception Back'), ['style' => 'margin-right:20px', 'class' => 'btn btn-info pull-right', 'id' => 'exception-back-multiple'])

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
$urlPassMultiple = Url::to(['sample-service/complete-pass-multiple']);
$urlDenyMultiple = Url::to(['sample-service/normal-back-multiple']);
$urlExceptMultiple = Url::to(['sample-service/exception-back-multiple']);

$script = <<< JS
$(function () {
  $('#my_test_dynagrid').on('click','#complete-pass-multiple',function(e){

      var selectRows = $('#my_test_grid').yiiGridView('getSelectedRows');

      $.post( "{$urlPassMultiple}", 
              {
                 pk : selectRows
              },
              function(result){
                  if(result == -1){
                      alert("您只能选择完成已经处于'测试中'的测试项");
                      return;
                  }
                $.pjax.reload({container:'#my_test_grid'});
              }
            );
   });


   // 退库操作
   $('#my_test_dynagrid').on('click','#normal-back-multiple',function(e){
       
      var selectRows = $('#my_test_grid').yiiGridView('getSelectedRows');

      $.post( "{$urlDenyMultiple}", 
              {
                 pk : selectRows
              },
              function(result){
                  if(result == -1){
                      alert("您只能选择退回'领取中'或者'测试中'的测试项");
                      return;
                  }
                $.pjax.reload({container:'#my_test_grid'});
              }
            );

      });
   // 异常退库   
   $('#my_test_dynagrid').on('click',"#exception-back-multiple",function(e) {
      var selectRows = $('#my_test_grid').yiiGridView('getSelectedRows');
              
      $.post( "{$urlExceptMultiple}", 
              {
                 pk : selectRows
              },
              function(result){
                   if(result == -1){
                      alert("您只能选择退回'领取中'或者'测试中'的测试项");
                      return;
                  }
                $.pjax.reload({container:'#my_test_grid'});
              }
            );

      });
      
});



$('#my_test_dynagrid').on('click','a[name="modalUpload"]',function(e){
        $('#uploadModal').modal('show')
                   .find('#uploadModalConent')
                   .load($(this).attr('href'));
        return false;
});


$('#closeUploadModal').on('click',function(e){
        $.pjax.reload({container:'#my_test_grid'});
});


JS;
$this->registerJs($script);
?>



