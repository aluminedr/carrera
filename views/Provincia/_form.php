<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Provincia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="provincia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idProvincia')->textInput() ?>

    <?= $form->field($model, 'nombreProvincia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'codigoIso31662')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
