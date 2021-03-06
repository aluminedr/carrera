<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Carrerapersonacopia */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="carrerapersonacopia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idTipoCarrera')->textInput() ?>

    <?= $form->field($model, 'idPersona')->textInput() ?>

    <?= $form->field($model, 'reglamentoAceptado')->textInput() ?>

    <?= $form->field($model, 'retiraKit')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
