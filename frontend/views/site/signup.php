<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use common\models\RegionProvince;
use common\models\RegionCity;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
 <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>Sam</b>ple</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg"></p>
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'user_name',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-Signup-user_name has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}{hint}'
                ])->textInput(['placeholder'=>Yii::t('frontend','User Name')]) ?>
   
      <?= $form->field($model, 'inputPassword',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-Signup-inputPassword has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>{error}{hint}'
                ])->textInput(['placeholder'=>Yii::t('frontend','Password')]) ?>

     <?= $form->field($model, 'reInputPassword',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-Signup-reInputPassword has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-log-in form-control-feedback"></span>{error}{hint}'
                ])->textInput(['placeholder'=>Yii::t('frontend','Retype password')]) ?>

    <?= $form->field($model, 'mobile_phone',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-Signup-mobile_phone has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-earphone form-control-feedback"></span>{error}{hint}'
                ])->textInput(['placeholder'=>Yii::t('frontend','Mobile Phone')]) ?>


    <?= $form->field($model, 'email',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-Signup-email has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>{error}{hint}'
                ])->textInput(['placeholder'=>Yii::t('frontend','Email')]) ?>

    <?= $form->field($model, 'company_name',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-Signup-company_name has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-th form-control-feedback"></span>{error}{hint}'
                ])->textInput(['placeholder'=>Yii::t('frontend','Company Name')]) ?>



   <legend class="text-info"><small> <?= Yii::t('frontend','Address') ?></small></legend>



    <?= $form->field($model, 'province',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-Signup-province has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-chevron-down form-control-feedback"></span>{error}{hint}'
                ])->dropDownList(
                      ArrayHelper::map(RegionProvince::find()->all(),'province_id','province_name'),
                      [
                        'prompt'=>Yii::t('frontend', 'Select Province'),
                        'onchange'=>'
                                 $.post("index.php?r=region/city&id='.'"+$(this).val(),
                                  function(data){
                                      
                                      $("select#signupform-city").html(data);
                                  }
                                  );'
                      ]) ?>


    <?= $form->field($model, 'city',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-Signup-city has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-chevron-down form-control-feedback"></span>{error}{hint}'
                ])->dropDownList(
                    ArrayHelper::map(RegionCity::find()->all(),'city_id','city_name'),
                    [
                      'prompt'=>Yii::t('frontend', 'Select City'),
                    ]) ?>


    <?= $form->field($model, 'detail_address',[
                    'options'=>[
                      'tag'=>'div',
                      'class'=>'form-group field-Signup-detail_address has-feedback required'
                    ],
                    'template'=>'{input}<span class="glyphicon glyphicon-home form-control-feedback"></span>{error}{hint}'
                ])->textArea(['rows'=>3,'placeholder'=>Yii::t('frontend','Detail Address')]) ?>

    <?= $form->field($model,'verifyCode')->widget(Captcha::className());?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('frontend', 'Sign Up') , ['class' => 'btn btn-success']) ?>
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
