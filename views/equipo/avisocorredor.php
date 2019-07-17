<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Equipo */

$this->title = "Nuevo corredor DNI ".$participante;
//\yii\web\YiiAsset::register($this);
?>
<div class='reglamento-container'>

     <div class="row">
        <h2> <?= "Mensaje del cambio de corredor"?></h2>
     </div>
    <br><br>
    <div class="row">   
        <div class="col-lg-5">
           <h2> <?= "Datos del nuevo corredor"?></h2>
          <br>
           <h4 style='color:#3A8816;'><strong><?= Html::encode($this->title)?></strong></h4>
           <ul>
              <li>Nombre de equipo:  <?= Html::encode($equipo->nombreEquipo)?></li>
           </ul>
        </div> 
        <?Php $this->title = "Corredor saliente DNI ".$corredor; ?>
        <div class="col-lg-5">
           <h2> <?= "Datos corredor saliente"?></h2>
          <br>
           <h4 style='color:#3A8816;'><strong><?= Html::encode($this->title)?></strong></h4>
           <ul>
              <li>Nombre de equipo:  <?= Html::encode($equipo->nombreEquipo)?></li>
           </ul>
          <br> 
        </div>
    </div>
     <br><br>
    <div class='row'>
        <?Php if(isset($mensaje)){
        echo "<strong style='color:#467EBB;'>".Html::encode($mensaje)."</strong>"; 
         }
        ?>  
    </div>
</div>
