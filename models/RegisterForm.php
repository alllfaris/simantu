<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $nama_mahasiswa;
    public $nim;
    public $kelas;

    public function rules()
    {
        return [
            [['username', 'email', 'password', 'nama_mahasiswa', 'nim', 'kelas'], 'required'],
            ['email', 'email'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Username ini sudah digunakan.'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Email ini sudah digunakan.'],
            ['nim', 'unique', 'targetClass' => '\app\models\Mahasiswa', 'message' => 'NIM ini sudah terdaftar.'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'email' => 'Alamat Email',
            'password' => 'Password',
            'nama_mahasiswa' => 'Nama Lengkap',
            'nim' => 'NIM',
            'kelas' => 'Kelas / Jurusan',
        ];
    }

    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->role = 'mahasiswa';
            $user->created_at = date('Y-m-d H:i:s');

            if ($user->save()) {
                $mahasiswa = new Mahasiswa();
                $mahasiswa->user_id = $user->id;
                $mahasiswa->nama_mahasiswa = $this->nama_mahasiswa;
                $mahasiswa->nim = $this->nim;
                $mahasiswa->kelas = $this->kelas;
                $mahasiswa->email = $this->email;

                if ($mahasiswa->save()) {
                    $transaction->commit();
                    return $user;
                }
            }
            $transaction->rollBack();
        } catch (\Exception $e) {
            $transaction->rollBack();
        }

        return null;
    }
}
