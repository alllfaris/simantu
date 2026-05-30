<?php

use yii\helpers\Html;
use app\models\PengumpulanTugas;

/** @var yii\web\View $this */

$this->title = 'Daftar Tugas Saya';
?>

<div class="container-fluid">

    <div class="card-custom p-4 mb-4">
        <h3 class="fw-bold mb-1">Daftar Tugas Saya</h3>
        <p class="text-muted mb-0">Lihat seluruh tugas dan status pengumpulan Anda di sini.</p>
    </div>

    <!-- Daftar Tugas -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="p-3 border-bottom" style="border-color:var(--color-card-border)!important;">
            <h6 class="fw-bold mb-0">Semua Tugas</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-borderless mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Judul Tugas</th>
                        <th class="d-none d-md-table-cell">Mata Kuliah</th>
                        <th class="d-none d-lg-table-cell">Deadline</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($tugasList)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada tugas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tugasList as $i => $tugas): ?>
                        <?php
                        $sudahKumpulTugas = $mahasiswa ? PengumpulanTugas::findOne([
                            'tugas_id' => $tugas->id,
                            'mahasiswa_id' => $mahasiswa->id,
                        ]) : null;
                        ?>
                        <tr>
                            <td class="ps-4 py-3 text-muted"><?= $i + 1 ?></td>
                            <td class="py-3 fw-medium"><?= Html::encode($tugas->judul_tugas) ?></td>
                            <td class="py-3 d-none d-md-table-cell">
                                <?= Html::encode($tugas->matkul->nama_matkul ?? '-') ?>
                            </td>
                            <td class="py-3 d-none d-lg-table-cell">
                                <?= $tugas->getDeadlineBadge() ?>
                            </td>
                            <td class="py-3">
                                <?php if ($sudahKumpulTugas): ?>
                                    <span class="badge-active">Sudah Kumpul</span>
                                <?php else: ?>
                                    <span class="badge-pending">Belum Kumpul</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3">
                                <?= Html::a('<i class="bi bi-eye"></i>',
                                    ['/mahasiswa/tugas-detail', 'id' => $tugas->id],
                                    ['class' => 'btn btn-sm btn-light border rounded-3']
                                ) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
