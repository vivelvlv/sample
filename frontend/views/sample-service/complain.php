<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SampleService */
/* @var $form yii\widgets\ActiveForm */

/* @property string $title
 * @property string $content
 */
?>

<div class="complain-form">


<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'title')->textInput() ?>
<?= $form->field($model, 'content')->textarea(['rows' => 10, 'sytle' => "resize:none"]) ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('frontend', 'Create')
        : Yii::t('frontend', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>