<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\AdminUser;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->user_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Admin Users'), 'url' => ['index']];
?>
<div class="user-view">

   <?php

    $attributes = [
            [
                'group'=>true,
                'label'=>Yii::t('backend','Basic Information'),
                'rowOptions'=>['class'=>'danger']
            ],

            [
                'columns' => [
                    [
                        'attribute'=>'user_name', 
                        'displayOnly'=>true,
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                    [
                        'attribute'=>'work_no', 
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                ],
            ],

            'email:email',
            [
                'columns' => [
                    [
                        'attribute'=>'mobile_phone', 
                        'displayOnly'=>true,
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                    [
                        'attribute'=>'office_phone', 
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                ],
            ],
            [
              'attribute'=>'image',
              'format'=>'raw',
              'value'=> Html::img(Yii::$app->upload->imageRootUrl.$model->image,['style'=>'width:auto;height:160px;']) ,
            ],
            [
              'attribute'=>'leader_id',
              'format'=>'raw',
              'value'=>  isset($model->leader) ? $model->leader->user_name :Yii::t('yii','(not set)'),
            ],
            [
                'group'=>true,
                'label'=>Yii::t('backend','Work Area'),
                'rowOptions'=>['class'=>'danger']
            ],
            [
                'label'=>'Lab',
                'value'=>$model->lab_building.'  '.$model->lab_floor.'  '.$model->lab_room
  
            ],
            [
                'label'=>'Office',
                'value'=>$model->office_building.'  '.$model->office_floor.'  '.$model->office_room
            ],
            [
                'group'=>true,
                'label'=>Yii::t('backend','Other Information'),
                'rowOptions'=>['class'=>'danger']
            ],
            [
                'attribute'=>'status',
                'value'=>$model->getStatusText()
            ],
            'entry_date:date',
            [
               'attribute'=>'leave_date',
               'format'=>'date',
               'visible'=>$model->status == AdminUser::STATUS_DELETED,   
            ],
            [
              'attribute'=>'role',
              'value'=>$model->getRoleName(),
            ]
    
        ];

         echo DetailView::widget([
            'model' => $model,
            'attributes' => $attributes,
            'bordered' => true,
            'striped' => true,
            'condensed' => false,
            'responsive' => true,
            'hover' => true,
            'hAlign'=>'left',
            'vAlign'=>'middle',  
            'mode'=>DetailView::MODE_VIEW,
            'panel'=>[
                'heading'=>'<h3 class="panel-title"><i class="fa fa-user-plus"></i>  '.$model->user_name.'</h3>',
                'type'=>DetailView::TYPE_INFO,
            ],
            'buttons1'=>'{view}'
    ]);

    ?>

</div>
