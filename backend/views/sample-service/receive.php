<?php

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


$this->title = Yii::t('backend', 'Receive Sample');
$this->params['breadcrumbs'][] = $this->title;
?>
    <br>
    <div class="container-fluid">

        <div class="col-md-3">
            <?php echo $this->render('_receive_search', ['model' => $searchModel]); ?>
        </div>
        <div class="col-md-9">
            <?php
            $gridColumns = [
                [
                    'class' => 'kartik\grid\CheckboxColumn',
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
                    'visible' => true,
                    'vAlign' => 'middle',
                ],
//                [
//                    'label' => Yii::t('backend', 'Sample Code'),
//                    'value' => 'sample.serial_number',
//                    'vAlign' => 'middle',
//                    'width' => '10%',
//                ],
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
                        'after' => Html::button(Yii::t('backend', 'Receive Samples'), ['class' => 'btn btn-success', 'id' => 'check-pass-multiple'])
                            .
                            Html::button(Yii::t('backend', 'Deny'), ['class' => 'btn btn-danger pull-right', 'id' => 'check-deny-multiple'])

                    ],
                    'toolbar' => [
                        ['content' => '{dynagrid}'],

                    ],
                    'options' => ['id' => 'receive_sample_grid']

                ],
                'options' => ['id' => 'receive_sample_dynagrid'] // a unique identifier is important
            ]);

            ?>
        </div>

    </div>
<?php
$urlPassMultiple = Url::to(['sample-service/receive-pass-multiple']);
$urlDenyMultiple = Url::to(['sample-service/receive-deny-multiple']);

$script = <<< JS
$(function () {
  $('#receive_sample_dynagrid').on('click','#check-pass-multiple',function(e){

      var selectRows = $('#receive_sample_grid').yiiGridView('getSelectedRows');

      $.post( "{$urlPassMultiple}", 
              {
                 pk : selectRows
              },
              function(){
                $.pjax.reload({container:'#receive_sample_grid'});
              }
            );
   });


   $('#receive_sample_dynagrid').on('click','#check-deny-multiple',function(e){
      var selectRows = $('#receive_sample_grid').yiiGridView('getSelectedRows');

      $.post( "{$urlDenyMultiple}", 
              {
                 pk : selectRows
              },
              function(){
                $.pjax.reload({container:'#receive_sample_grid'});
              }
            );

      });
});

JS;
$this->registerJs($script);
?>