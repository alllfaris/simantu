<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Tugas;

/**
 * TugasSearch represents the model behind the search form of `app\models\Tugas`.
 */
class TugasSearch extends Tugas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'dosen_id', 'matkul_id'], 'integer'],
            [['judul_tugas', 'deskripsi', 'deadline', 'status', 'created_at'], 'safe'],
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
        $query = Tugas::find();

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
            'matkul_id' => $this->matkul_id,
            'deadline' => $this->deadline,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'judul_tugas', $this->judul_tugas])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
