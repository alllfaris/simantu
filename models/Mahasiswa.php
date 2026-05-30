<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mahasiswa".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $nama_mahasiswa
 * @property string|null $nim
 * @property string|null $kelas
 * @property string|null $email
 * @property string|null $created_at
 *
 * @property PengumpulanTugas[] $pengumpulanTugas
 * @property UserModel $user
 */
class Mahasiswa extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mahasiswa';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'nim', 'kelas', 'email'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['nama_mahasiswa'], 'required'],
            [['created_at'], 'safe'],
            [['nama_mahasiswa', 'email'], 'string', 'max' => 150],
            [['nim', 'kelas'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserModel::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'nama_mahasiswa' => 'Nama Mahasiswa',
            'nim' => 'Nim',
            'kelas' => 'Kelas',
            'email' => 'Email',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[PengumpulanTugas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, ['mahasiswa_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(UserModel::class, ['id' => 'user_id']);
    }

}
