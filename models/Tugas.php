<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tugas".
 *
 * @property int $id
 * @property int|null $dosen_id
 * @property int|null $matkul_id
 * @property string $judul_tugas
 * @property string|null $deskripsi
 * @property string|null $deadline
 * @property string|null $status
 * @property string|null $created_at
 *
 * @property Dosen $dosen
 * @property Matkul $matkul
 * @property PengumpulanTugas[] $pengumpulanTugas
 */
class Tugas extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_AKTIF = 'aktif';
    const STATUS_SELESAI = 'selesai';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tugas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dosen_id', 'matkul_id', 'deskripsi', 'deadline'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'aktif'],
            [['dosen_id', 'matkul_id'], 'integer'],
            [['judul_tugas'], 'required'],
            [['deskripsi', 'status'], 'string'],
            [['deadline', 'created_at'], 'safe'],
            [['judul_tugas'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['dosen_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dosen::class, 'targetAttribute' => ['dosen_id' => 'id']],
            [['matkul_id'], 'exist', 'skipOnError' => true, 'targetClass' => Matkul::class, 'targetAttribute' => ['matkul_id' => 'id']],
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
            'matkul_id' => 'Matkul ID',
            'judul_tugas' => 'Judul Tugas',
            'deskripsi' => 'Deskripsi',
            'deadline' => 'Deadline',
            'status' => 'Status',
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
     * Gets query for [[Matkul]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMatkul()
    {
        return $this->hasOne(Matkul::class, ['id' => 'matkul_id']);
    }

    /**
     * Gets query for [[PengumpulanTugas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, ['tugas_id' => 'id']);
    }

    public function getIsSelesai()
    {
        $totalMahasiswa = Mahasiswa::find()->count();
        $sudahKumpul = PengumpulanTugas::find()
            ->where(['tugas_id' => $this->id])
            ->count();
        return $sudahKumpul >= $totalMahasiswa && $totalMahasiswa > 0;
    }

    public function getProgressPengumpulan()
    {
        $totalMahasiswa = Mahasiswa::find()->count();
        $sudahKumpul = PengumpulanTugas::find()
            ->where(['tugas_id' => $this->id])
            ->count();
        return $sudahKumpul . ' / ' . $totalMahasiswa . ' Mahasiswa';
    }

    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_AKTIF => 'aktif',
            self::STATUS_SELESAI => 'selesai',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusAktif()
    {
        return $this->status === self::STATUS_AKTIF;
    }

    public function setStatusToAktif()
    {
        $this->status = self::STATUS_AKTIF;
    }

    /**
     * @return bool
     */
    public function isStatusSelesai()
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function setStatusToSelesai()
    {
        $this->status = self::STATUS_SELESAI;
    }

    public function getDeadlineBadge()
    {
        if (!$this->deadline) {
            return '<span class="text-muted">Tidak ada deadline</span>';
        }

        $deadline = new \DateTime($this->deadline);
        $today    = new \DateTime();

        $diff = (int)$today->diff($deadline)->format("%r%a");

        $dateText = Yii::$app->formatter->asDate($this->deadline, 'dd MMM yyyy');

        if ($diff < 0) {
            return '<span class="badge bg-danger">
                ' . $dateText . ' — Terlambat ' . abs($diff) . ' hari
            </span>';
        }

        if ($diff === 0) {
            return '<span class="badge bg-warning text-dark">
                ' . $dateText . ' — Hari ini!
            </span>';
        }

        if ($diff <= 3) {
            return '<span class="badge bg-warning text-dark">
                ' . $dateText . ' — ' . $diff . ' hari lagi
            </span>';
        }

        return '<span class="badge bg-success">
            ' . $dateText . ' — ' . $diff . ' hari lagi
        </span>';
    }

    public function checkHoliday()
    {
        $year     = date('Y', strtotime($this->deadline));
        $cacheKey = 'holidays_' . $year;

        $holidays = Yii::$app->cache->get($cacheKey);

        if ($holidays === false) {
            $url      = 'https://api-hari-libur.vercel.app/api?year=' . $year;
            $response = @file_get_contents($url);

            if ($response === false) {
                $this->is_holiday   = 0;
                $this->holiday_name = null;
                return;
            }

            $json     = json_decode($response, true);
            $holidays = $json['data'] ?? []; // ← ambil dari key 'data'
            Yii::$app->cache->set($cacheKey, $holidays, 60 * 60 * 24 * 30);
        }

        $deadlineStr        = date('Y-m-d', strtotime($this->deadline));
        $this->is_holiday   = 0;
        $this->holiday_name = null;

        foreach ($holidays as $holiday) {
            if (isset($holiday['date']) && $holiday['date'] === $deadlineStr) {
                $this->is_holiday   = 1;
                $this->holiday_name = $holiday['description'] ?? 'Hari Libur'; // ← 'description' bukan 'name'
                break;
            }
        }
    }

    // TAMBAH: beforeSave
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->deadline && ($this->isAttributeChanged('deadline') || $insert)) {
                $this->checkHoliday();
            }
            return true;
        }
        return false;
    }
}
