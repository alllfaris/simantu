<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "pengumpulan_tugas".
 *
 * @property int $id
 * @property int|null $tugas_id
 * @property int|null $mahasiswa_id
 * @property string|null $file_tugas
 * @property string|null $catatan
 * @property string|null $status_kumpul
 * @property string|null $waktu_kumpul
 * @property string|null $created_at
 *
 * @property Mahasiswa $mahasiswa
 * @property Tugas $tugas
 */
class PengumpulanTugas extends \yii\db\ActiveRecord
{

    public $uploadFile;

    /**
     * ENUM field values
     */
   
    const STATUS_TEPAT_WAKTU = 'Tepat Waktu';
    const STATUS_TERLAMBAT = 'Terlambat';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pengumpulan_tugas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tugas_id', 'mahasiswa_id', 'file_tugas', 'link_tugas', 'catatan', 'waktu_kumpul'], 'default', 'value' => null],
            [['tugas_id', 'mahasiswa_id'], 'integer'],
            [
                ['uploadFile'],
                'file',
                'extensions' => 'pdf,zip',
                'checkExtensionByMimeType' => false,
                'skipOnEmpty' => true, 
            ],
            [['link_tugas'], 'url', 'defaultScheme' => 'https', 'skipOnEmpty' => true], // ✅ validasi URL
            [['catatan', 'status_kumpul', 'link_tugas'], 'string'],
            [['waktu_kumpul', 'created_at'], 'safe'],
            [['file_tugas'], 'string', 'max' => 255],
            ['status_kumpul', 'in', 'range' => array_keys(self::optsStatus())],
            [['tugas_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tugas::class, 'targetAttribute' => ['tugas_id' => 'id']],
            [['mahasiswa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Mahasiswa::class, 'targetAttribute' => ['mahasiswa_id' => 'id']],
            [
                ['tugas_id', 'mahasiswa_id'],
                'unique',
                'targetAttribute' => ['tugas_id', 'mahasiswa_id'],
                'message' => 'Kamu sudah pernah mengumpulkan tugas ini.',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tugas_id' => 'Tugas ID',
            'mahasiswa_id' => 'Mahasiswa ID',
            'file_tugas' => 'File Tugas',
            'link_tugas' => 'Link Google Drive', 
            'catatan' => 'Catatan',
            'status_kumpul' => 'Status Kumpul',
            'waktu_kumpul' => 'Waktu Kumpul',
            'created_at' => 'Created At',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'waktu_kumpul',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $tugas = Tugas::findOne($this->tugas_id);
                
                // Ambil hanya bagian tanggal, lalu tambah 23:59:59
                $deadlineDate = date('Y-m-d', strtotime($tugas->deadline));
                $deadline     = new \DateTime($deadlineDate . ' 23:59:59');
                $waktuKumpul  = new \DateTime();

                $this->status_kumpul = ($waktuKumpul <= $deadline)
                    ? self::STATUS_TEPAT_WAKTU
                    : self::STATUS_TERLAMBAT;
            }
            return true; 
        }
        return false;
    }

    /**
     * Gets query for [[Mahasiswa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, ['id' => 'mahasiswa_id']);
    }

    /**
     * Gets query for [[Tugas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTugas()
    {
        return $this->hasOne(Tugas::class, ['id' => 'tugas_id']);
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_TEPAT_WAKTU => 'Tepat Waktu',
            self::STATUS_TERLAMBAT => 'Terlambat',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status_kumpul];
    }

    public function isStatusTepatWaktu()
    {
        return $this->status_kumpul === self::STATUS_TEPAT_WAKTU;
    }

    public function isStatusTerlambat()
    {
        return $this->status_kumpul === self::STATUS_TERLAMBAT;
    }

    /**
     * @return bool
     */
    
}
