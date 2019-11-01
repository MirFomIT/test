<?php

/* @var $this yii\web\View */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\City;
use app\models\Shop;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <?php if(Yii::$app->session->hasFlash('card_number_ok')):?>
                <section id="code-random">
                    <p>Введите пин-код</p>
                    <?php $form = ActiveForm::begin([
                        'id' => 'form-pin-code',
                        'options' => ['method' => 'post'],
                    ]);?>
                    <?=$form->field($card,'pin')->textInput(['autofocus' => true,'placeholder' => 'пин код карты'])?>
                    <div class="form-group">
                        <div class="col-12 col-xs-8 col-sm-12 col-md-11 col-lg-12 form-group">
                            <?= Html::submitButton('OK', ['class' => 'btn btn-success', 'name'=>'pin_ok']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end();?>
                </section>
            <?php else: ?>
                <?php $form = ActiveForm::begin([
                    'id' => 'code-random-form',
                    'options' => ['method' => 'post'],
                ]); ?>

                <div class="col-xs-8 col-xs-offset-2 col-lg-8 col-lg-offset-2">
                    <label for="pin">код проверки</label>
                    <input name='code_random_new'/>

                </div>

                <div class="form-group">
                    <div class="col-12 col-xs-8 col-sm-12 col-md-11 col-lg-12 form-group">
                        <?= Html::submitButton('OK', ['class' => 'btn btn-success', 'name'=>'ok']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            <?php endif;?>

        </div>

     </div>
</div>
