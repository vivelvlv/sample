<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Role;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="role-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput($model->isNewRecord?[]:['readonly'=>true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php //echo $form->field($model,'permissions')->checkboxList(Role::getAuthItemsText(),
                                                         // ['separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;']); ?>
    <div class="well">
    <label class="control-label" for="role-permissions"><?= Yii::t('backend','Permissions')?></label>
    <input type="hidden" name="Role[permissions]" value="">
    <div id="role-permissions">
    <?php 

		$categoryAttributes = Role::categoryAttribute();
		$permissions = Role::getPermissions();
		$roleCategory = Role::roleCategory();

		function getCategoryPermissions($category,$param_permissions,$param_roleCategory)
		{
			$result = [];
			foreach ($param_permissions as $key=>$value)
			{
				if(
					   isset($param_roleCategory[$key]) 
					&& $param_roleCategory[$key] == $category
					)
				{
					$result[] = $key;
				}
			}
			return $result;
		}

		function permissionAttribute($permission)
		{
			$roleAttribute = Role::roleAttributes();
			if(isset($roleAttribute[$permission]))
			{
				return $roleAttribute[$permission];
			}
			else
			{
				return $permission;
			}
		}

		function permissionCheck($permission,$model)
		{
			if($model->isNewRecord)
			{
				return "";
			}
			$currentPermissions = $model->permissions;
			if(isset($currentPermissions) && in_array($permission, $currentPermissions))
			{
				return "checked";
			}
			return "";
		}
     ?>


     <?php foreach($categoryAttributes as $index=>$category_attr):?>
     
         <legend class="text-info"><small> <?= $category_attr ?></small></legend>
     	<?php $categoryPermissions = getCategoryPermissions($index,$permissions,$roleCategory) ?>
     	<?php $count = count($categoryPermissions) ?>
     	<table class="table">
     	<tbody>
     	<?php for($i = 0; $i < $count ; $i=$i+5):?>
     		<tr>
     			<td width="20%">
     				<?php if($i < $count): ?>
     				    <input type="checkbox" name="Role[permissions][]" value="<?=$categoryPermissions[$i]?>" <?=permissionCheck($categoryPermissions[$i],$model)?>> 
     				                                        <?= permissionAttribute($categoryPermissions[$i])?>
     			    <?php endif; ?>
     			</td>
     			<td width="20%">
     				<?php if($i +1 < $count): ?>
     					 <input type="checkbox" name="Role[permissions][]" value="<?=$categoryPermissions[$i+1]?>" <?=permissionCheck($categoryPermissions[$i+1],$model)?>> 
     					                                    <?= permissionAttribute($categoryPermissions[$i+1])?>
     			    <?php endif; ?>
     			</td>
     			<td width="20%">
     				<?php if($i+2 < $count): ?>
     					<input type="checkbox" name="Role[permissions][]" value="<?=$categoryPermissions[$i+2]?>" <?=permissionCheck($categoryPermissions[$i+2],$model)?>> 
     					                                    <?= permissionAttribute($categoryPermissions[$i+2])?>
     			    <?php endif; ?>
     			</td>
     			<td width="20%">
     				<?php if($i+3 < $count): ?>
     					<input type="checkbox" name="Role[permissions][]" value="<?=$categoryPermissions[$i+3]?>" <?=permissionCheck($categoryPermissions[$i+3],$model)?>> 
     					                                     <?= permissionAttribute($categoryPermissions[$i+3])?>
     			    <?php endif; ?>
     			</td>
     			<td width="20%">
     				<?php if($i+4 < $count): ?>
     					<input type="checkbox" name="Role[permissions][]" value="<?=$categoryPermissions[$i+4]?>" <?=permissionCheck($categoryPermissions[$i+4],$model)?>> 
     					                                    <?= permissionAttribute($categoryPermissions[$i+4])?>
     			    <?php endif; ?>
     			</td>
     		</tr>
     	<?php endfor;?>
     	</tbody>
     	</table>
     <?php endforeach; ?>

   </div>
    <div class="help-block"></div>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('backend', 'Create') : Yii::t('backend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
