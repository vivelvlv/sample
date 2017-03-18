<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
 <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>Sam</b>ple-backend</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg"></p>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-loginform-username has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}{hint}'
                ])->textInput(['placeholder'=>Yii::t('backend','User Name')]) ?>

                <?= $form->field($model, 'password',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-loginform-password has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>{error}{hint}'
                ])->passwordInput() ?>
                <?= $form->field($model,'verifyCode')->widget(Captcha::className());?>
                 <div class="row">
                 <?= $form->field($model, 'rememberMe')->checkbox() ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('backend','Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
     </div>
</div>
<?php
$script= <<<JS

 $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
        });
      });
JS;
  $this->registerJS($script);
 ?>
