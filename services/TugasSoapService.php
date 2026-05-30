<?php

namespace app\services;

use app\models\Tugas;
use app\models\PengumpulanTugas;

class TugasSoapService
{
    /**
     * @param int $matkul_id
     * @return array
     */
    public function getTugasByMatkul(int $matkul_id): array
    {
        $result = Tugas::find()
            ->where(['matkul_id' => $matkul_id])
            ->asArray()
            ->all();

        return $result ?: [];
    }

    /**
     * @param int $tugas_id
     * @return array
     */
    public function getPengumpulanByTugas(int $tugas_id): array
    {
        $result = PengumpulanTugas::find()
            ->where(['tugas_id' => $tugas_id])
            ->joinWith('mahasiswa')
            ->asArray()
            ->all();

        return $result ?: [];
    }
}