<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use common\models\AdminUser;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->user_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Users'), 'url' => ['index']];
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
                        'attribute'=>'email', 
                        'format'=>'email',
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'mobile_phone', 
                        'displayOnly'=>true,
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                    [
                        'attribute'=>'created_at', 
                        'format'=>'date',
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                ],
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'company_name', 
                        'displayOnly'=>true,
                        'valueColOptions'=>['style'=>'width:30%']
                    ],
                    [
                        'attribute'=>'company_tax', 
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true
                    ],
                ],
            ],
            [
                'group'=>true,
                'label'=>Yii::t('backend','Address'),
                'rowOptions'=>['class'=>'danger']
            ],
            [
                'columns' => [
                    [
                        'attribute'=>'province', 
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true,
                        'value'=>  isset($model->userProvince) ? $model->userProvince->province_name :Yii::t('yii','(not set)'),
                    ],
                    [
                        'attribute'=>'city', 
                        'valueColOptions'=>['style'=>'width:30%'], 
                        'displayOnly'=>true,
                        'value'=>  isset($model->userCity) ? $model->userCity->city_name :Yii::t('yii','(not set)'),
                    ],
                ],
            ],
          'detail_address',
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
