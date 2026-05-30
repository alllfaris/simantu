<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "matkul".
 *
 * @property int $id
 * @property int|null $dosen_id
 * @property string $nama_matkul
 * @property string|null $kode_matkul
 * @property string|null $semester
 * @property string|null $created_at
 *
 * @property Dosen $dosen
 * @property Tugas[] $tugas
 */
class Matkul extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'matkul';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dosen_id', 'kode_matkul', 'semester'], 'default', 'value' => null],
            [['dosen_id'], 'integer'],
            [['nama_matkul'], 'required'],
            [['created_at'], 'safe'],
            [['nama_matkul'], 'string', 'max' => 150],
            [['kode_matkul'], 'string', 'max' => 50],
            [['semester'], 'string', 'max' => 20],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::class, 'targetAttribute' => ['dosen_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dosen_id' => 'Dosen ID',
            'nama_matkul' => 'Nama Matkul',
            'kode_matkul' => 'Kode Matkul',
            'semester' => 'Semester',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Dosen]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDosen()
    {
        return $this->hasOne(Dosen::class, ['id' => 'dosen_id']);
    }

    /**
     * Gets query for [[Tugas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTugas()
    {
        return $this->hasMany(Tugas::class, ['matkul_id' => 'id']);
    }

}
