<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GestoresSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="gestores-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'idGestor') ?>

    <?= $form->field($model, 'nombreGestor') ?>

    <?= $form->field($model, 'apellidoGestor') ?>

    <?= $form->field($model, 'telefonoGestor') ?>

    <?= $form->field($model, 'idUsuario') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
