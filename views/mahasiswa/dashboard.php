<?php

use yii\helpers\Html;
use app\models\Tugas;
use app\models\PengumpulanTugas;

/** @var yii\web\View $this */

$this->title = 'Dashboard Mahasiswa';
?>

<div class="container-fluid">

    <!-- Header -->
    <div class="card-custom p-4 mb-4">
        <h3 class="fw-bold mb-1">
            Selamat Datang,
            <?= Html::encode(
                $mahasiswa->nama_mahasiswa
                ?? Yii::$app->user->identity->username
            ) ?>
            👋
        </h3>

        <p class="text-muted mb-0">
            NIM: <?= Html::encode($mahasiswa->nim ?? '-') ?>
            &nbsp; | &nbsp;
            Kelas: <?= Html::encode($mahasiswa->kelas ?? '-') ?>
        </p>
    </div>

    <!-- Statistik -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card-custom p-4">
                <div class="text-muted small">
                    Total Tugas
                </div>

                <h2 class="fw-bold mb-0">
                    <?= $totalTugas ?>
                </h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-custom p-4">
                <div class="text-muted small">
                    Sudah Dikumpulkan
                </div>

                <h2 class="fw-bold text-success mb-0">
                    <?= $sudahKumpul ?>
                </h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-custom p-4">
                <div class="text-muted small">
                    Belum Dikumpulkan
                </div>

                <h2 class="fw-bold text-danger mb-0">
                    <?= $belumKumpul ?>
                </h2>
            </div>
        </div>

    </div>

</div>