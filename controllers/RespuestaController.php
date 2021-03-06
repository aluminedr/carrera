<?php

namespace app\controllers;

use Yii;
use app\models\Respuesta;
use app\models\RespuestaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\RespuestaOpcion;
use app\models\Resultado;
use app\models\Pregunta;
use yii\filters\AccessControl;
use app\models\Permiso;

/**
 * RespuestaController implements the CRUD actions for Respuesta model.
 */
class RespuestaController extends Controller
{

    public static function instanciaRespuesta(){
        $resp = new Respuesta();
        return $resp;
    }
    /**
     * Recibe las respuestas de las encuestas y guarda cada una en la base de datos
     */
    public function actionArmarespuesta(){

        $respuesta=$_REQUEST;
        $msg="";
         foreach($respuesta as $clave=>$valor){
            if(is_numeric($clave)){
                if(is_array($valor)){
                    foreach($valor as $unValor){
                        if(is_numeric($unValor)){
                            $opcion=RespuestaOpcion::findOne($unValor);
                            $resp['respValor']=$opcion->opRespvalor;
                        }else{
                            $resp['respValor']=$unValor;
                        }
                        $resp['idPregunta']=$clave;
                        $resp['idPersona']=1;
                        if(!$this->guardarespuesta($resp)){
                            $msg.="respuesta a: ".$resp['respValor']." no se guardo -- ";
                        }else{
                            $msg.="respuesta a: ".$resp['respValor']." guardada OK -- ";
                        }
                    }
                }else{
                    if(is_numeric($valor)){
                        $opcion=RespuestaOpcion::findOne($valor);
                        $resp['respValor']=$opcion->opRespvalor;
                    }else{
                        $resp['respValor']=$valor;
                    }
                    $resp['idPregunta']=$clave;
                    $resp['idPersona']=1;
                    if(!$this->guardarespuesta($resp)){
                        $msg.="respuesta a: ".$resp['respValor']." no se guardo -- ";
                    }else{
                        $msg.="respuesta a: ".$resp['respValor']." guardada OK -- ";
                    }
                }
            }
                
            }
            $r=$msg;
            return $this->render('verrespuesta', ['model' => $r]);
        
    }

    private function guardarespuesta($resp){
        $carga=false;
        $model=new Respuesta();
        
        $model->respValor=$resp['respValor'];
        $model->idPregunta=$resp['idPregunta'];
        $model->idPersona=$resp['idPersona'];

        if($model->save(false)){
            $carga=true;
        }
        return $carga;
    }

    public function actionRespuesta(){   

        $respuesta=new Respuesta();
        if ($respuesta->load(Yii::$app->request->post())){
        return $this->render('respuestaform',['respuesta'=>$respuesta]);
        }

    }
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access'=>[
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback'=>function($rule,$action){
                            return Permiso::requerirRol('administrador') && Permiso::requerirActivo(1);
                        }
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback'=>function($rule,$action){
                            return Permiso::requerirRol('gestor') && Permiso::requerirActivo(1);
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Respuesta models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(Permiso::requerirRol('administrador')){
        $this->layout='/main2';
    }elseif(Permiso::requerirRol('gestor')){
        $this->layout='/main3';
    }
        $searchModel = new RespuestaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination=[
            'pageSize'=>15,
        ];
        $pregunta=null;

        if(isset($_REQUEST['idPregunta'])){
            $dataProvider->query->andWhere('respuesta.idPregunta='.$_REQUEST['idPregunta']);
            $pregunta=Pregunta::find()->where(['idPregunta'=>$_REQUEST['idPregunta']])->one();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pregunta'=>$pregunta,
        ]);
    }

    /**
     * Displays a single Respuesta model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {   if(Permiso::requerirRol('administrador')){
        $this->layout='/main2';
    }elseif(Permiso::requerirRol('gestor')){
        $this->layout='/main3';
    }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Respuesta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {   if(Permiso::requerirRol('administrador')){
        $this->layout='/main2';
    }elseif(Permiso::requerirRol('gestor')){
        $this->layout='/main3';
    }
        $model = new Respuesta();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idRespuesta]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Respuesta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {   if(Permiso::requerirRol('administrador')){
        $this->layout='/main2';
    }elseif(Permiso::requerirRol('gestor')){
        $this->layout='/main3';
    }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->idRespuesta]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Respuesta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {   if(Permiso::requerirRol('administrador')){
        $this->layout='/main2';
    }elseif(Permiso::requerirRol('gestor')){
        $this->layout='/main3';
    }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Respuesta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Respuesta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {   if(Permiso::requerirRol('administrador')){
        $this->layout='/main2';
    }elseif(Permiso::requerirRol('gestor')){
        $this->layout='/main3';
    }
        if (($model = Respuesta::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
