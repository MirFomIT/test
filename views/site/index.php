<?php

/* @var $this yii\web\View */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\City;
use app\models\Shop;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <?php if(Yii::$app->session->hasFlash('code_random')):?>
                <section id="code-random">
                    <p>Проверочный код</p>
                    <input value="<?=$code_random?>"</input>
                    <button class = "col-12 col-xs-4 col-sm-4 col-md-4 col-lg-4 btn buy">
                        <a href="<?= Url::to(['site/bonus-card']);?>">далее</a>
                    </button>

                </section>
            <?php else: ?>
            <?php $form = ActiveForm::begin([
                'id' => 'card-form',
                'options' => ['method' => 'post'],
            ]); ?>

            <div class="col-xs-8 col-xs-offset-2 col-lg-8 col-lg-offset-2">

               <!-- <input
                        el="OTP"
                        placeholder="●●&nbsp;&nbsp;●●"
                        type="tel"
                        style="width: 68px;"
                        class="input control-input-field otp_inp"
                        role="input"
                        data-mask="00&nbsp;&nbsp;00"
                        autocomplete="off"
                >-->

                <?= $form->field($card, 'number')->textInput(['autofocus' => true, 'placeholder'=>'X XXXXXX XXXXXX','pattern' => '\d{1} \d{6} \d{6}','class' => 'masked'])->label('Номер карты')?>


                <?= $form->field($card, 'phone')->textInput(['placeholder'=>'XXX XXX XX XX', 'pattern' => '\d{3}\d{3}\-\d{4}', 'class' => 'masked'])->label('Телефон: +7')?>

            </div>

            <div class="form-group">
                <div class="col-12 col-xs-8 col-sm-12 col-md-11 col-lg-12 form-group">
                    <?= Html::submitButton('отправить проверочный код', ['class' => 'btn btn-success', 'name' =>'code_random'])?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
            <?php endif;?>
        </div>

    </div>
</div>
<script>
    var masking = {

        // User defined Values
        //maskedInputs : document.getElementsByClassName('masked'), // add with IE 8's death
        maskedInputs : document.querySelectorAll('.masked'), // kill with IE 8's death
        maskedNumber : 'XdDmMyY9',
        maskedLetter : '_',

        init: function () {
            masking.setUpMasks(masking.maskedInputs);
            masking.maskedInputs = document.querySelectorAll('.masked'); // Repopulating. Needed b/c static node list was created above.
            masking.activateMasking(masking.maskedInputs);
        },

        setUpMasks: function (inputs) {
            var i, l = inputs.length;

            for(i = 0; i < l; i++) {
                masking.createShell(inputs[i]);
            }
        },

        // replaces each masked input with a shall containing the input and it's mask.
        createShell : function (input) {
            var text = '',
                placeholder = input.getAttribute('placeholder');

            input.setAttribute('maxlength', placeholder.length);
            input.setAttribute('data-placeholder', placeholder);
            input.removeAttribute('placeholder');

            text = '<span class="shell">' +
                '<span aria-hidden="true" id="' + input.id +
                'Mask"><i></i>' + placeholder + '</span>' +
                input.outerHTML +
                '</span>';

            input.outerHTML = text;
        },

        setValueOfMask : function (e) {
            var value = e.target.value,
                placeholder = e.target.getAttribute('data-placeholder');

            return "<i>" + value + "</i>" + placeholder.substr(value.length);
        },

        // add event listeners
        activateMasking : function (inputs) {
            var i, l;

            for (i = 0, l = inputs.length; i < l; i++) {
                if (masking.maskedInputs[i].addEventListener) { // remove "if" after death of IE 8
                    masking.maskedInputs[i].addEventListener('keyup', function(e) {
                        masking.handleValueChange(e);
                    }, false);
                } else if (masking.maskedInputs[i].attachEvent) { // For IE 8
                    masking.maskedInputs[i].attachEvent("onkeyup", function(e) {
                        e.target = e.srcElement;
                        masking.handleValueChange(e);
                    });
                }
            }
        },

        handleValueChange : function (e) {
            var id = e.target.getAttribute('id');

            switch (e.keyCode) { // allows navigating thru input
                case 20: // caplocks
                case 17: // control
                case 18: // option
                case 16: // shift
                case 37: // arrow keys
                case 38:
                case 39:
                case 40:
                case  9: // tab (let blur handle tab)
                    return;
            }

            document.getElementById(id).value = masking.handleCurrentValue(e);
            document.getElementById(id + 'Mask').innerHTML = masking.setValueOfMask(e);

        },

        handleCurrentValue : function (e) {
            var isCharsetPresent = e.target.getAttribute('data-charset'),
                placeholder = isCharsetPresent || e.target.getAttribute('data-placeholder'),
                value = e.target.value, l = placeholder.length, newValue = '',
                i, j, isInt, isLetter, strippedValue;

            // strip special characters
            strippedValue = isCharsetPresent ? value.replace(/\W/g, "") : value.replace(/\D/g, "");

            for (i = 0, j = 0; i < l; i++) {
                var x =
                    isInt = !isNaN(parseInt(strippedValue[j]));
                isLetter = strippedValue[j] ? strippedValue[j].match(/[A-Z]/i) : false;
                matchesNumber = masking.maskedNumber.indexOf(placeholder[i]) >= 0;
                matchesLetter = masking.maskedLetter.indexOf(placeholder[i]) >= 0;

                if ((matchesNumber && isInt) || (isCharsetPresent && matchesLetter && isLetter)) {

                    newValue += strippedValue[j++];

                } else if ((!isCharsetPresent && !isInt && matchesNumber) || (isCharsetPresent && ((matchesLetter && !isLetter) || (matchesNumber && !isInt)))) {
                    // masking.errorOnKeyEntry(); // write your own error handling function
                    return newValue;

                } else {
                    newValue += placeholder[i];
                }
                // break if no characters left and the pattern is non-special character
                if (strippedValue[j] == undefined) {
                    break;
                }
            }
            if (e.target.getAttribute('data-valid-example')) {
                return masking.validateProgress(e, newValue);
            }
            return newValue;
        },

        validateProgress : function (e, value) {
            var validExample = e.target.getAttribute('data-valid-example'),
                pattern = new RegExp(e.target.getAttribute('pattern')),
                placeholder = e.target.getAttribute('data-placeholder'),
                l = value.length, testValue = '';

            //convert to months
            if (l == 1 && placeholder.toUpperCase().substr(0,2) == 'MM') {
                if(value > 1 && value < 10) {
                    value = '0' + value;
                }
                return value;
            }
            // test the value, removing the last character, until what you have is a submatch
            for ( i = l; i >= 0; i--) {
                testValue = value + validExample.substr(value.length);
                if (pattern.test(testValue)) {
                    return value;
                } else {
                    value = value.substr(0, value.length-1);
                }
            }

            return value;
        },

        errorOnKeyEntry : function () {
            // Write your own error handling
        }
    }

    masking.init();
</script>