<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Device');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php  echo $this->render('/sample/_tab_view'); ?>
<br>
<div class="device-index">


    <br>
    <br>
    <?php
       //create
       Modal::begin([
            'header'=>'<h4>'.Yii::t('backend', 'Device').' </h4>',
            'id'=>'createModel',
            'size'=>'modal-md',
           ]);
       echo "<div id='createModalConent'></div>";
       Modal::end();

       //update
       Modal::begin([
            'header'=>'<h4>'.Yii::t('backend', 'Device').' </h4>',
            'id'=>'updateModal',
            'size'=>'modal-md',
           ]);
       echo "<div id='updateModalConent'></div>";
       Modal::end();

      //view
      Modal::begin([
            'header'=>'<h4>'.Yii::t('backend', 'Device').' </h4>',
            'id'=>'viewModal',
            'size'=>'modal-md',
            'footer'=>'<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>'
           ]);
       echo "<div id='viewModalConent'></div>";
       Modal::end();  

    ?>
  <?php 
    $gridColumns= [
            ['class' => 'yii\grid\SerialColumn'],

            [
              'attribute'=>'name',
              'width'=>'30%'
            ],
            [
              'attribute'=>'description',
              'width'=>'40%'
            ],
            [
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'is_show', 
                'trueLabel' => Yii::t('backend','Shown'), 
                'falseLabel' => Yii::t('backend','Hidden'),
                'vAlign'=>'middle',
                'width'=>'20%'
            ],

            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{view} &nbsp;&nbsp;{update}',
              'buttons'=>[
                 'view'=> function($url,$model,$key)
                 {
                   $options = [
                        'title' => Yii::t('yii', 'View'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                        'name'=>'modalView',
                    ];
                   return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                 },
                 'update'=> function($url,$model,$key)
                 {
                    $options = [
                        'title' => Yii::t('yii', 'Update'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                        'name'=>'modalUpdate'
                    ];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                 },
              ],
              'headerOptions'=>['width'=>'100'],
            ],
        ];
    echo  GridView::widget([
          'dataProvider'=>$dataProvider,
          'filterModel'=>$searchModel,
          'columns'=>$gridColumns,
          'headerRowOptions'=>['class'=>'kartik-sheet-style'],
          'filterRowOptions'=>['class'=>'kartik-sheet-style'],
          'pjax'=>true, // pjax is set to always true for this demo
          // set your toolbar
          'toolbar'=> [
              ['content'=>
              Html::button('<i class="glyphicon glyphicon-plus"></i>',['value'=>Url::to(['device/create']),
                                                               'title'=>Yii::t('backend', 'Create Device'),
                                                                'class'=>'btn btn-success',
                                                                'id'=>'createSampleButton',
                                                               ])
              ],
          ],
          'bordered'=>true,
          'striped'=>true,
          'hover'=>true,
          'panel'=>[
              
              //'heading'=>$heading,
          ],

          'panel'=>[
            'type'=>GridView::TYPE_INFO,
            'heading'=>'<h3 class="panel-title"><i class="fa fa-flask"></i>  '.Yii::t('backend','Device').'</h3>',
            'before' =>  '<div style="padding-top: 7px;"><em>*'.Yii::t('backend','Show All Devices').'</em></div>',
          ], 
          'options'=>['id'=>'device_grid']
      ]);
   ?>


</div>


<?php
$script = <<< JS

$('#device_grid-pjax').on('click','a[name="modalUpdate"]',function(e){
        $('#updateModal').modal('show')
                   .find('#updateModalConent')
                   .load($(this).attr('href'));
        return false;
});

$('#device_grid-pjax').on('click','a[name="modalView"]',function(e){
        $('#viewModal').modal('show')
                   .find('#viewModalConent')
                   .load($(this).attr('href'));
        return false;
});


$('#device_grid-pjax').on('click','#createSampleButton',function(e){
        $('#createModel').modal('show')
                   .find('#createModalConent')
                   .load($(this).attr('value'));
        return false;
});

JS;
$this->registerJs($script);
?>
