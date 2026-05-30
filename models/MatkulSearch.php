<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Matkul;

/**
 * MatkulSearch represents the model behind the search form of `app\models\Matkul`.
 */
class MatkulSearch extends Matkul
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'dosen_id'], 'integer'],
            [['nama_matkul', 'kode_matkul', 'semester', 'created_at'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = Matkul::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'dosen_id' => $this->dosen_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'nama_matkul', $this->nama_matkul])
            ->andFilterWhere(['like', 'kode_matkul', $this->kode_matkul])
            ->andFilterWhere(['like', 'semester', $this->semester]);

        return $dataProvider;
    }
}
