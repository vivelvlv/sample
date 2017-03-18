<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model common\models\Article */

$this->title = Yii::t('backend', 'Announce');

?>
<?php  echo $this->render('/sample/_tab_view'); ?>
<br>
<div class="announce">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textArea(['rows' => 3]) ?>

    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
        'clientOptions' => [
            'filebrowserUploadUrl' =>  Url::to(['article/upload']) ]

    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Update'), ['class' =>  'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>