<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dosen".
 *
 * @property int $id
 * @property string $nama_dosen
 * @property string|null $nidn
 * @property string|null $email
 * @property string|null $created_at
 *
 * @property Matkul[] $matkuls
 * @property Tugas[] $tugas
 */
class Dosen extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dosen';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nidn', 'email'], 'default', 'value' => null],
            [['nama_dosen'], 'required'],
            [['created_at'], 'safe'],
            [['nama_dosen', 'email'], 'string', 'max' => 150],
            [['nidn'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nama_dosen' => 'Nama Dosen',
            'nidn' => 'Nidn',
            'email' => 'Email',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Matkuls]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMatkuls()
    {
        return $this->hasMany(Matkul::class, ['dosen_id' => 'id']);
    }

    /**
     * Gets query for [[Tugas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTugas()
    {
        return $this->hasMany(Tugas::class, ['dosen_id' => 'id']);
    }

}
