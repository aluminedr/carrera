<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "persona".
 *
 * @property int $idPersona
 * @property string $nombrePersona
 * @property string $apellidoPersona
 * @property string $fechaNacPersona
 * @property int $idSexoPersona
 * @property string $nacionalidadPersona
 * @property string $telefonoPersona
 * @property string $mailPersona
 * @property int $idUsuario
 * @property int $mailPersonaValidado
 * @property string $codigoValidacionMail
 * @property string $codigoRecuperarCuenta
 * @property int $idPersonaDireccion
 * @property int $idFichaMedica
 * @property string $fechaInscPersona
 * @property int $idPersonaEmergencia
 * @property int $idEstadoPago
 * @property int $deshabilitado
 *
 * @property Usuario $usuario
 * @property Personaemergencia $personaEmergencia
 * @property Personadireccion $personaDireccion
 * @property Fichamedica $fichaMedica
 * @property Sexo $sexoPersona
 * @property Estadopago $estadoPago
 */
class Persona extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'persona';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fechaNacPersona', 'fechaInscPersona'], 'safe'],
            [['idSexoPersona', 'idUsuario', 'mailPersonaValidado', 'idPersonaDireccion', 'idFichaMedica', 'idPersonaEmergencia', 'idEstadoPago', 'deshabilitado'], 'integer'],
            [['mailPersona', 'idUsuario'], 'required','message' => 'El e-mail es obligatorio'],
            ['mailPersona','email'],
            [['nombrePersona', 'apellidoPersona', 'nacionalidadPersona', 'mailPersona'], 'string', 'max' => 64],
            [['telefonoPersona'], 'number'],
            [['codigoValidacionMail', 'codigoRecuperarCuenta'], 'string', 'max' => 16],
            [['idUsuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['idUsuario' => 'idUsuario']],
            [['idPersonaEmergencia'], 'exist', 'skipOnError' => true, 'targetClass' => Personaemergencia::className(), 'targetAttribute' => ['idPersonaEmergencia' => 'idPersonaEmergencia']],
            [['idPersonaDireccion'], 'exist', 'skipOnError' => true, 'targetClass' => Personadireccion::className(), 'targetAttribute' => ['idPersonaDireccion' => 'idPersonaDireccion']],
            [['idFichaMedica'], 'exist', 'skipOnError' => true, 'targetClass' => Fichamedica::className(), 'targetAttribute' => ['idFichaMedica' => 'idFichaMedica']],
            [['idSexoPersona'], 'exist', 'skipOnError' => true, 'targetClass' => Sexo::className(), 'targetAttribute' => ['idSexoPersona' => 'idSexo']],
            [['idEstadoPago'], 'exist', 'skipOnError' => true, 'targetClass' => Estadopago::className(), 'targetAttribute' => ['idEstadoPago' => 'idEstadoPago']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idPersona' => 'Id Persona',
            'nombrePersona' => 'Nombre Persona',
            'apellidoPersona' => 'Apellido Persona',
            'fechaNacPersona' => 'Fecha Nac Persona',
            'idSexoPersona' => 'Id Sexo Persona',
            'nacionalidadPersona' => 'Nacionalidad Persona',
            'telefonoPersona' => 'Telefono Persona',
            'mailPersona' => 'Mail Persona',
            'idUsuario' => 'Id Usuario',
            'mailPersonaValidado' => 'Mail Persona Validado',
            'codigoValidacionMail' => 'Codigo Validacion Mail',
            'codigoRecuperarCuenta' => 'Codigo Recuperar Cuenta',
            'idPersonaDireccion' => 'Id Persona Direccion',
            'idFichaMedica' => 'Id Ficha Medica',
            'fechaInscPersona' => 'Fecha Insc Persona',
            'idPersonaEmergencia' => 'Id Persona Emergencia',
            'idEstadoPago' => 'Id Estado Pago',
            'deshabilitado' => 'Deshabilitado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::className(), ['idUsuario' => 'idUsuario']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaEmergencia()
    {
        return $this->hasOne(Personaemergencia::className(), ['idPersonaEmergencia' => 'idPersonaEmergencia']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonaDireccion()
    {
        return $this->hasOne(Personadireccion::className(), ['idPersonaDireccion' => 'idPersonaDireccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFichaMedica()
    {
        return $this->hasOne(Fichamedica::className(), ['idFichaMedica' => 'idFichaMedica']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSexoPersona()
    {
        return $this->hasOne(Sexo::className(), ['idSexo' => 'idSexoPersona']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstadoPago()
    {
        return $this->hasOne(Estadopago::className(), ['idEstadoPago' => 'idEstadoPago']);
    }
}
