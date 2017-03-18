<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = Yii::t('backend', 'Region');
if($level == 1)
{
   $this->title = Yii::t('backend', 'Level One Region');
}
else if($level == 2)
{
   $this->title = Yii::t('backend', 'Level Two Region');
   $this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Level One Region'), 'url' => ['index','level'=>1]];
}
else if($level == 3)
{
   $this->title = Yii::t('backend', 'Level Three Region');
   $this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Level One Region'), 'url' => ['index','level'=>1]];
   $this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Level Two Region'), 'url' => ['index','level'=>2,'id'=>$country_id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="region-index">
 <?php 
    $parent_id = 0;
    if($level == 2)
    {
        $parent_id = $country_id;
    }
    else if($level == 3)
    {
        $parent_id = $province_id;
    }
 ?>
 <p>
    <?= Html::button(Yii::t('backend', 'Create Region'),['value'=>Url::to('index.php?r=region/create&level='.$level.'&id='.$parent_id),
                                                                'class'=>'btn btn-success',
                                                                'id'=>'modalButton']) ?>
 </p>

 <?php
    Modal::begin([
            'header'=>'<h4>'.Yii::t('backend', 'Region').' </h4>',
            'id'=>'modal',
            'size'=>'modal-lg',
        ]);
    echo "<div id='modalConent'></div>";
    Modal::end();
 ?>

 <br>
<table class="table table-striped">
   <tbody>
     <?php 

        $count = count($regions);

        function region_data($level,$region,$parent_id)
        {
 
            echo Html::a($region['name'], ['region/update','level'=>$level,'id'=>$parent_id,'update_id'=>$region['id']],  [
                        'title' => Yii::t('yii', 'Update'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'name'=>'modalUpdate'
                    ]);

             echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            
              if($level < 3)
              {
                 echo Html::a('<span class="glyphicon glyphicon-th"></span>', ['region/index','level'=>$level+1,'id'=>$region['id']]);
              }   

             echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
             
             $options = [
                        'title' => Yii::t('yii', 'Delete'),
                        'aria-label' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post'
                    ];
             echo  Html::a('<span class="glyphicon glyphicon-trash"></span>', ['region/delete','level'=>$level,'id'=>$parent_id,'delete_id'=>$region['id']], $options);
        }

     ?>
     <?php for($i =0; $i < $count; $i = $i+3): ?> 
      <tr>
         <td width="33%"> 
             <?php region_data($level,$regions[$i],$parent_id);?>
         </td>
         <td width="33%"> 
           <?php 
               if($i+1 < $count)
               {
                  region_data($level,$regions[$i+1],$parent_id);
               }
           ?>
         </td>
         <td width="34%"> 
           <?php 
              if( $i+2 < $count)
              {
                 region_data($level,$regions[$i+2],$parent_id);
              }
           ?>
         </td>
      </tr>
     <?php endfor;?>

   </tbody>
</table>

<?php
$script = <<< JS
$(function(){
    $('#modalButton').click(function(){
        $('#modal').modal('show')
                   .find('#modalConent')
                   .load($(this).attr('value'));
    });
   
    $('a[name="modalUpdate"]').click(function(){
        $('#modal').modal('show')
                   .find('#modalConent')
                   .load($(this).attr('href'));
        return false;
    });

});
JS;
$this->registerJs($script);
?>