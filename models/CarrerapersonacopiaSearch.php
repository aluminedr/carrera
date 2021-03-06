<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Carrerapersonacopia;

/**
 * CarrerapersonacopiaSearch represents the model behind the search form of `app\models\Carrerapersonacopia`.
 */
class CarrerapersonacopiaSearch extends Carrerapersonacopia
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['idTipoCarrera', 'idPersona', 'reglamentoAceptado', 'retiraKit'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Carrerapersonacopia::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'idTipoCarrera' => $this->idTipoCarrera,
            'idPersona' => $this->idPersona,
            'reglamentoAceptado' => $this->reglamentoAceptado,
            'retiraKit' => $this->retiraKit,
        ]);

        return $dataProvider;
    }
}
