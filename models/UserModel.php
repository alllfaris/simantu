<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string|null $auth_key
 * @property string|null $email
 * @property string $role
 * @property string|null $created_at
 *
 * @property Mahasiswa[] $mahasiswas
 */
class UserModel extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_MAHASISWA = 'mahasiswa';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_key', 'email'], 'default', 'value' => null],
            [['username', 'password_hash', 'role'], 'required'],
            [['role'], 'string'],
            [['created_at'], 'safe'],
            [['username'], 'string', 'max' => 100],
            [['password_hash', 'auth_key'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 150],
            ['role', 'in', 'range' => array_keys(self::optsRole())],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'email' => 'Email',
            'role' => 'Role',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Mahasiswas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, ['user_id' => 'id']);
    }


    /**
     * column role ENUM value labels
     * @return string[]
     */
    public static function optsRole()
    {
        return [
            self::ROLE_ADMIN => 'admin',
            self::ROLE_MAHASISWA => 'mahasiswa',
        ];
    }

    /**
     * @return string
     */
    public function displayRole()
    {
        return self::optsRole()[$this->role];
    }

    /**
     * @return bool
     */
    public function isRoleAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function setRoleToAdmin()
    {
        $this->role = self::ROLE_ADMIN;
    }

    /**
     * @return bool
     */
    public function isRoleMahasiswa()
    {
        return $this->role === self::ROLE_MAHASISWA;
    }

    public function setRoleToMahasiswa()
    {
        $this->role = self::ROLE_MAHASISWA;
    }
}
