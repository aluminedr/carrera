<?php
/**
 * Created by PhpStorm.
 * User: ariel
 * Date: 29/08/19
 * Time: 23:31
 */
namespace app\controllers;

use app\models\Controlpago;
use app\models\Equipo;
use app\models\Gestores;
use app\models\Grupo;
use app\models\Pago;
use app\models\Persona;
use app\models\Result;
use app\models\Usuario;
use yii\web\Controller;

class EstadisticaController extends  Controller{

    public function actionNoinscriptos(){
        $this->layout = '/main2';
        $usuariosNoInscriptos=[];
        $usuariariosTodos=Usuario::find()->all();

        foreach ($usuariariosTodos as $usuario){
               if(Persona::findOne(['idUsuario'=>$usuario->idUsuario])==null){
                   if(Gestores::findOne(['idUsuario'=>$usuario->idUsuario])==null){
                       $usuariosNoInscriptos[]=$usuario;
                   }

               }
        }

        return $this->render('index',['usuariosNoInscriptos'=>$usuariosNoInscriptos]);
    }

    public function actionGenerales(){
        $this->layout = '/main2';
        $equipos=Equipo::findAll(['deshabilitado'=>0]);
        $equiposIncompletos=0;
        $personasFaltanInscribirse=0;
        $equiposOcupandoCuposSinPagar=[];
        $dniCapitanes=[];
        foreach ($equipos as $equipo){
            //cuenta las personas que hay cada  equipo
            $totalParticipantesEquipo=Grupo::findAll(['idEquipo'=>$equipo->idEquipo]);
            $totalParticipantesEquipoNum=count($totalParticipantesEquipo);
            //si la cantidad de personas en el equipo es diferente a la que dice el equipo que tendra
            if($equipo->cantidadPersonas!=$totalParticipantesEquipoNum){
                $dniCapitanes[]=$equipo->dniCapitan;

                //cuenta cuantas personas le faltan al equipo y luego las suma en la variable
                $personasFaltanInscribirse=$personasFaltanInscribirse+($equipo->cantidadPersonas-$totalParticipantesEquipoNum);
                //suma la cantidad de equipos incompletos
                $equiposIncompletos++;

            }

            //si el equipo no pago y esta ocupando cupo
            if($equipo->pagoInscripcion()==false && $equipo->capEquipoEnListaEspera()==false){
                $equiposOcupandoCuposSinPagar[]=$equipo;
            }

        }
        return $this->render('generales',['equipos'=>$equipos,'equiposIncompletos'=>$equiposIncompletos,'personasFaltanInscribirse'=>$personasFaltanInscribirse,'dniCapitanes'=>$dniCapitanes,'equiposOcupandoCuposSinPagar'=>$equiposOcupandoCuposSinPagar]);
    }

    public function actionEquiposincompletosinpagar(){
        $this->layout = '/main2';
        $equiposDos=Equipo::findAll(['cantidadPersonas'=>2,'deshabilitado'=>0]);
        $equiposCuatro=Equipo::findAll(['cantidadPersonas'=>4,'deshabilitado'=>0]);

        $equiposDosIncompletosSinPagar=[];
        $equiposDosIncompletosSinPagarCuatro=[];
        //equipos de dos personas incompletos que no pagaron
        foreach ($equiposDos as $equipoDos){
            if($equipoDos->cantidadPersonas!=$equipoDos->cuposOcupados()){
                if($equipoDos->pagoInscripcion()==false){
                    if($equipoDos->capEquipoEnListaEspera()==false){
                        $equiposDosIncompletosSinPagar[]=$equipoDos;
                    }

                }
            }

        }
        //equipos de cuatro personas incompletos que no pagaron
        foreach ($equiposCuatro as $equipoCuatro){
            if($equipoCuatro->cantidadPersonas!=$equipoCuatro->cuposOcupados()){
                if($equipoCuatro->pagoInscripcion()==false){
                    if($equipoCuatro->capEquipoEnListaEspera()==false){
                        $equiposDosIncompletosSinPagarCuatro[]=$equipoCuatro;
                    }

                }
            }

        }

        return $this->render('equiposincompletos',['equiposIncompletosSinPagar'=>$equiposDosIncompletosSinPagar,'equiposDosIncompletosSinPagarCuatro'=>$equiposDosIncompletosSinPagarCuatro]);

    }

    //retorna los equipos abonados  que tienen participantes en espera
    public function actionEquiposabonadosparticipanteespera(){
        $this->layout = '/main2';
        $equipos=Equipo::findAll(['deshabilitado'=>0]);
        $equiposAbonadosConParticipanteEspera=[];
        $cuposRequeridos=0;
        $personasOcupandoCupoDefinitivo=0;
        $equiposAbonadosIncompletos=[];
        $equiposCuatroPersonas=$equipos=Equipo::findAll(['deshabilitado'=>0,'cantidadPersonas'=>4]);
        $equiposCuatroAbonadosIncompletos=[];


        foreach ($equipos as $equipo ){
            if($equipo->pagoInscripcion()){
                if($equipo->cuposOcupados()!=$equipo->cantidadPersonas){
                    $equiposAbonadosIncompletos[]=$equipo;
                }

                $personasEquipo=$equipo->personasEnElEquipo();
                foreach ($personasEquipo as $persona){
                    if($persona->estoyEnEspera()){
                        $cuposRequeridos=$cuposRequeridos+1;
                        $equiposAbonadosConParticipanteEspera[]=$equipo;
                    }else{
                        $personasOcupandoCupoDefinitivo=$personasOcupandoCupoDefinitivo+1;
                    }
                }
            }
        }
        foreach ($equiposCuatroPersonas as $equipoCuatro){
            if($equipoCuatro->pagoInscripcion()){
                if($equipoCuatro->cuposOcupados()!=$equipoCuatro->cantidadPersonas){
                    $equiposCuatroAbonadosIncompletos[]=$equipoCuatro;
                }
            }

        }


        return $this->render('equiposabonadosespera',['equiposAbonadosConParticipanteEspera'=>$equiposAbonadosConParticipanteEspera,'cuposRequeridos'=>$cuposRequeridos,'personasOcupandoCupoDefinitivo'=>$personasOcupandoCupoDefinitivo,'equiposAbonadosIncompletos'=>$equiposAbonadosIncompletos,'equiposCuatroAbonadosIncompletos'=>$equiposCuatroAbonadosIncompletos]);


    }

    public function actionCapitanesespera(){
        $this->layout = '/main2';
        $equipos=Equipo::findAll(['deshabilitado'=>0]);
        $equiposCapEspera=[];
        foreach ($equipos as $equipo){
            if($equipo->capEquipoEnListaEspera()){
                $equiposCapEspera[]=$equipo;
            }
        }

        return $this->render('capitanesespera',['equiposCapEspera'=>$equiposCapEspera]);
    }

    public function actionDefinitivo(){
        $this->layout = '/main2';
        $equipos=Equipo::findAll(['deshabilitado'=>0]);
        $equiposPagados=[];
        $cuposTotal=0;
        foreach ($equipos as $equipo){
            if($equipo->pagoInscripcion()){
                $equiposPagados[]=$equipo;
                $cantidadPersonasEquipo=count($cantidadPersonasEquipo=$equipo->personasEnElEquipo());
                $cuposTotal=$cuposTotal+$cantidadPersonasEquipo;
                //echo $cantidadPersonasEquipo;
                //die();
            }

        }

        return $this->render('definitivos',['equiposPagados'=>$equiposPagados,'cuposTotal'=>$cuposTotal]);
    }

    public function actionEquiposnopagos(){
        $equipos=Equipo::findAll(['deshabilitado'=>0]);
        $this->layout = '/main2';
        $equiposNoAbonados=[];
        foreach ($equipos as $equipo){
            if($equipo->pagoInscripcion()==false){
                $equiposNoAbonados[]=$equipo;
            }
        }

        return $this->render('nopagos',['equiposNoAbonados'=>$equiposNoAbonados]);


    }

    public function  actionConfirmados(){
        $this->layout = '/main2';
        //recreativa
        $equiposCuatroRecreativa=[];
        $equiposDosRecreativa=[];
        //competitiva
        $equiposDosCompetitiva=[];
        $equiposCuatroCompetitiva=[];

        $equipos=Equipo::find()->where(['deshabilitado'=>0])->orderBy(['dniCapitan'=>SORT_ASC])->all();
        foreach($equipos as $equipo){
            if($equipo->pagoInscripcion()){
                if($equipo->cantidadPersonas==2 && $equipo->idTipoCarrera==1){
                    $equiposDosRecreativa[]=$equipo;
                }
                if($equipo->cantidadPersonas==4 && $equipo->idTipoCarrera==1){
                    $equiposCuatroRecreativa[]=$equipo;
                }
                if($equipo->cantidadPersonas==2 && $equipo->idTipoCarrera==2){
                    $equiposDosCompetitiva[]=$equipo;
                }
                if($equipo->cantidadPersonas==4 && $equipo->idTipoCarrera==2){
                    $equiposCuatroCompetitiva[]=$equipo;
                }
            }
        }
        return $this->render('confirmadosequipos',['equiposDosRecreativa'=>$equiposDosRecreativa,'equiposCuatroRecreativa'=>$equiposCuatroRecreativa,'equiposDosCompetitiva'=>$equiposDosCompetitiva,'equiposCuatroCompetitiva'=>$equiposCuatroCompetitiva]);

    }

    public function actionPersonasconfirmadas(){
        $this->layout = '/main2';
        $equipos=Equipo::find()->where(['deshabilitado'=>0])->orderBy(['dniCapitan'=>SORT_ASC])->all();
        $personasDosRecreativo=[];
        $personaCuatroRecreativo=[];
        //competitiva
        $personasDosCompetitiva=[];
        $personasCuatroCompetitiva=[];
        foreach ($equipos as $equipo){
            if($equipo->pagoInscripcion()){
                if($equipo->cantidadPersonas==2 && $equipo->idTipoCarrera==1){
                    $personasGrupo=$equipo->personasEnElEquipo();
                    foreach ($personasGrupo as $persona){
                        $personasDosRecreativo[]=$persona;
                    }

                }
                if($equipo->cantidadPersonas==4 && $equipo->idTipoCarrera==1){

                    $personasGrupo=$equipo->personasEnElEquipo();
                    foreach ($personasGrupo as $persona){
                        $personasCuatroRecreativo[]=$persona;
                    }

                }
                if($equipo->cantidadPersonas==2 && $equipo->idTipoCarrera==2){
                    $personasGrupo=$equipo->personasEnElEquipo();
                    foreach ($personasGrupo as $persona){
                        $personasDosCompetitiva[]=$persona;
                    }
                }
                if($equipo->cantidadPersonas==4 && $equipo->idTipoCarrera==2){
                    $personasGrupo=$equipo->personasEnElEquipo();
                    foreach ($personasGrupo as $persona){
                        $personasCuatroCompetitiva[]=$persona;
                    }
                }


            }
        }

        return $this->render('personasconfirmadas',['personasDosRecreativo'=>$personasDosRecreativo,'personasCuatroRecreativo'=>$personasCuatroRecreativo,'personasDosCompetitiva'=>$personasDosCompetitiva,'personasCuatroCompetitiva'=>$personasCuatroCompetitiva]);



    }

    public function actionEmailpersonasconfirmadas(){
        $this->layout = '/main2';
        $equipos=Equipo::find()->where(['deshabilitado'=>0])->orderBy(['dniCapitan'=>SORT_ASC])->all();
        $personasConfirmadas=[];

        foreach ($equipos as $equipo){
            if($equipo->pagoInscripcion()){
                $personasGrupo=$equipo->personasEnElEquipo();
                foreach ($personasGrupo as $persona){
                    $personasConfirmadas[]=$persona;
                }


            }
        }

        return $this->render('emailpersonasconfirmadas',['personasConfirmadas'=>$personasConfirmadas]);

    }

    public function actionSeguro(){
        $this->layout = '/main2';
        $equipos=Equipo::find()->where(['deshabilitado'=>0])->orderBy(['dniCapitan'=>SORT_ASC])->all();
        $personasConfirmadas=[];
        foreach ($equipos as $equipo){
            if($equipo->pagoInscripcion()){
                $personasGrupo=$equipo->personasEnElEquipo();
                foreach ($personasGrupo as $persona){
                    $personasConfirmadas[]=$persona;
                }


            }
        }
        return $this->render('seguro',['personasConfirmadas'=>$personasConfirmadas]);


    }
    public function actionGanadores(){
        //return $this->goBack();

        //$resultados=Result::find()->orderBy(['total'=>SORT_ASC])->all();
        //$resultadoss=Result::find();
        $tipoCarrera=null;
        $cantPersonas=null;
        $resultados=Result::find()->where(['categoria'=>1])->andFilterWhere(['cantPersonas'=>2])->orderBy(['total'=>SORT_ASC])->all();
        if(isset($_GET['tipoCarrera'])){
            if($_GET['tipoCarrera']==2 or $_GET['tipoCarrera']==1){
                $tipoCarrera=$_GET['tipoCarrera'];
                if($_GET['cantPersonas']){
                    $cantPersonas=$_GET['cantPersonas'];
                    $resultados=Result::find()->where(['categoria'=>$tipoCarrera])->andFilterWhere(['cantPersonas'=>$cantPersonas])->orderBy(['total'=>SORT_ASC])->all();

                }

            }else{
                $resultados=Result::find()->orderBy(['total'=>SORT_ASC])->all();


            }
        }




        return $this->render('ganadores',['resultados'=>$resultados,'tipoCarrera'=>$tipoCarrera,'cantPersonas'=>$cantPersonas]);
    }

    public function actionPagostransferencias(){
        $this->layout = '/main2';
        $cont=1;
        $entidad=null;
        $importe_pagado=null;
        if(isset($_GET['entidad']) && $_GET['entidad']!=null){
            $entidad=$_GET['entidad'];
            $pagosFiltro=Pago::find()->where(['like','entidadPago','%'.$_GET['entidad'].'%',false])->all();
            $pagos=[];
            foreach ($pagosFiltro as $pago){
                $controlPago=Controlpago::findOne(['idPago'=>$pago->idPago]);
                if($controlPago!=null && $controlPago->chequeado){
                    if($_GET['importe_pagado']!=null){
                        if($controlPago->pago->importePagado==$_GET['importe_pagado']){
                            $pagos[]=$controlPago;
                            $importe_pagado=$_GET['importe_pagado'];
                        }
                    }else{
                        $pagos[]=$controlPago;
                    }
                }


            }

            return $this->render('pagostransferencias',['pagos'=>$pagos,'entidad'=>$entidad,'importe_pagado'=>$importe_pagado]);
        }


        //$list= BaseYii::$app->db->createCommand('select p.idPago, p.importePagado,p.entidadPago,controlpago.fechaPago,controlpago.fechachequeado,persona.nombrePersona,persona.apellidoPersona,equipo.nombreEquipo from controlpago INNER JOIN pago as p ON controlpago.idPago=p.idPago INNER JOIN persona ON p.idPersona=persona.idPersona INNER JOIN equipo ON p.idEquipo=equipo.idEquipo where controlpago.chequeado=1 AND p.entidadPago!="Universidad nacional del comahue" AND p.entidadPago!="ByB indumentaria deportiva"')->queryAll();
        $pagosChequeados=Controlpago::findAll(['chequeado'=>1]);
        $pagos=[];
        //print_r($list);
        foreach ($pagosChequeados as $pago){
            if($pago->pago->entidadPago!="Universidad nacional del comahue"){
                if($pago->pago->entidadPago!="ByB indumentaria deportiva"){
                    if($pago->pago->entidadPago!='ByB Indumentaria Deportiva-Gimnacio Terra'){
                        if($pago->pago->entidadPago!='UNCo'){
                            if($pago->pago->entidadPago!='Universidad Nacional del Comahue'){
                                if($pago->pago->entidadPago!='Universidas nacional del comahue'){
                                    if($pago->pago->entidadPago!='UNIVERSIDAD NACIONAL DEL COMAHUE'){
                                        if($pago->pago->entidadPago!='B%B indumentaria deportiva'){
                                            if($pago->pago->entidadPago!='GIMNASIO ESCONDIDO'){
                                                if($pago->pago->entidadPago!='Byb'){
                                                    if($pago->pago->entidadPago!='byb'){
                                                        if($pago->pago->entidadPago!='Unco'){
                                                            if($pago->pago->entidadPago!='universidad nacional del comahue'){
                                                                if($pago->pago->entidadPago!='universidad'){
                                                                    if($pago->pago->entidadPago!='BB Indumetaria -Gimnasio Terra Centro'){
                                                                        if($pago->pago->entidadPago!='Efectivo'){
                                                                            if($pago->pago->entidadPago!='Gimnasio Terra'){
                                                                                if($pago->pago->entidadPago!='Efectivo en gimnasio'){
                                                                                    if($pago->pago->entidadPago!='Terra'){
                                                                                        if($pago->pago->entidadPago!='Polideportivo'){
                                                                                            if($pago->pago->entidadPago!='Universidad nacional del comahue 2 equipos'){
                                                                                                if($pago->pago->entidadPago!='Terra'){
                                                                                                    if($pago->pago->entidadPago!='Escondido'){
                                                                                                        $pagos[]=$pago;
                                                                                                        /*$porciones = explode("/", $pago->pago->imagenComprobante);
                                                                                                        $nombreArchivo=$porciones[2];
                                                                                                        $path= \Yii::getAlias('@webroot');
                                                                                                        if (!copy($path."/archivo/pagoinscripcion/$nombreArchivo", $path."/archivo/pagoinscripcion/pagostransferencias/$nombreArchivo")) {
                                                                                                            echo "Error al copiar". $pago->pago->imagenComprobante;
                                                                                                        }*/
                                                                                                    }

                                                                                                }
                                                                                            }

                                                                                        }

                                                                                    }

                                                                                }

                                                                            }

                                                                        }

                                                                    }

                                                                }

                                                            }

                                                        }

                                                    }

                                                }

                                            }

                                        }
                                    }

                                }

                            }


                        }

                    }

                }
            }

        }
        return $this->render('pagostransferencias',['pagos'=>$pagos]);

    }
}