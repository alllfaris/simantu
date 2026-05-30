<?php

use app\models\Dosen;
use app\models\Mahasiswa;
use app\models\Matkul;
use app\models\Tugas;

$this->title = 'Dashboard';

$totalDosen = Dosen::find()->count();
$totalMahasiswa = Mahasiswa::find()->count();
$totalMatkul = Matkul::find()->count();
$totalTugas = Tugas::find()->count();

?>

<div class="mb-4">
    <h1 class="fw-bold">
        Dashboard Sistem Manajemen Tugas
    </h1>

    <p class="text-muted">
        Selamat datang di aplikasi SiManTu berbasis Yii Framework.
    </p>
</div>

<div class="row g-4">

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h5>Total Dosen</h5>
            <h2 class="fw-bold text-success">
                <?= $totalDosen ?>
            </h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h5>Total Mahasiswa</h5>
            <h2 class="fw-bold text-primary">
                <?= $totalMahasiswa ?>
            </h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h5>Total Mata Kuliah</h5>
            <h2 class="fw-bold text-warning">
                <?= $totalMatkul ?>
            </h2>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm p-3">
            <h5>Total Tugas</h5>
            <h2 class="fw-bold text-danger">
                <?= $totalTugas ?>
            </h2>
        </div>
    </div>

</div>