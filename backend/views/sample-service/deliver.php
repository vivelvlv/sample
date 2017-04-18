<?php

use common\models\AdminUser;
use kartik\widgets\Select2;
use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

use common\models\Service;
use common\models\User;

use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::t('backend', 'Deliver Sample');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
$gridColumns = [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'headerOptions' => ['class' => 'kartik-sheet-style',
            'order' => DynaGrid::ORDER_FIX_LEFT]
    ],
    [
        'attribute' => 'received_at',
        'value' => 'received_at',
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
        'visible' => true,
        'vAlign' => 'middle',
    ],
//    [
//        'label' => Yii::t('backend', 'Sample Code'),
//        'value' => 'sample.serial_number',
//        'vAlign' => 'middle',
//        'width' => '10%',
//    ],
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
            'before' => '<div style="padding-top: 7px;"><em>*' . Yii::t('backend', 'Show All Services') . '</em></div>',
            'after' => '<div class="col-md-2">' . Select2::widget([
                    'name' => 'admin_user',
                    'data' => AdminUser::userAttributeLabel(),
                    'options' => [
                        'placeholder' => Yii::t('backend', 'Select Stuff'),
                        'multiple' => false
                    ],
                ]) . '</div>' . Html::button(Yii::t('backend', 'Deliver'), ['class' => 'btn btn-success', 'id' => 'deliver-pass-multiple']) .
                '&nbsp;&nbsp;' . Html::button(Yii::t('backend', 'Print BarCodes'), ['class' => 'btn btn-success pull-right', 'id' => 'print-barcodes'])
        ],
        'toolbar' => [
            ['content' => '{dynagrid}'],
        ],
        'options' => ['id' => 'deliver_sample_grid']
    ],
    'options' => ['id' => 'deliver_sample_dynagrid'] // a unique identifier is important
]);

?>

<?php
$urlPassMultiple = Url::to(['sample-service/deliver-pass-multiple']);
$urlPrintBarCodes = Url::to(['sample-service/print-barcodes']);


$script = <<< JS
$(function () {
  $('#deliver_sample_dynagrid').on('click','#deliver-pass-multiple',function(e){
   
      var selectRows = $('#deliver_sample_grid').yiiGridView('getSelectedRows');
      var user = $('select[name="admin_user"]').val();
      if(!user){
          alert("尚未选择用户");
          return;
      }
      $.post( "{$urlPassMultiple}", 
              {
                 pk : selectRows,
                 adu : user
              },
              function(){
                $.pjax.reload({container:'#deliver_sample_grid'});
              }
            );
   });
  $('#deliver_sample_dynagrid').on('click','#print-barcodes',function(e){
   
      var selectRows = $('#deliver_sample_grid').yiiGridView('getSelectedRows');
      
      if(!selectRows || selectRows.length == 0){
          alert("请选择输出的测试服务项");
          return;
      }
      
      var url = "{$urlPrintBarCodes}"+"&id="+selectRows;

      window.location.href = url;
      
   });
});

JS;
$this->registerJs($script);
?>