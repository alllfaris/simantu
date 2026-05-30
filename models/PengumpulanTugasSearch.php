<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PengumpulanTugas;

/**
 * PengumpulanTugasSearch represents the model behind the search form of `app\models\PengumpulanTugas`.
 */
class PengumpulanTugasSearch extends PengumpulanTugas
{
    public $nama_mahasiswa;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tugas_id', 'mahasiswa_id'], 'integer'],
            [['file_tugas', 'catatan', 'status_kumpul', 'waktu_kumpul', 'created_at', 'nama_mahasiswa'], 'safe'],
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
        $query = PengumpulanTugas::find()->joinWith('mahasiswa');

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
            'tugas_id' => $this->tugas_id,
            'mahasiswa_id' => $this->mahasiswa_id,
            'waktu_kumpul' => $this->waktu_kumpul,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'file_tugas', $this->file_tugas])
            ->andFilterWhere(['like', 'catatan', $this->catatan])
            ->andFilterWhere(['like', 'status_kumpul', $this->status_kumpul])
            ->andFilterWhere(['like', 'mahasiswa.nama_mahasiswa', $this->nama_mahasiswa]);

        return $dataProvider;
    }
}
