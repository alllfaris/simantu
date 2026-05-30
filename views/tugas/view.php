<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Tugas $model */

$this->title = 'Detail Tugas';
$this->params['breadcrumbs'][] = ['label' => 'Tugas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tugas-view">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Detail Tugas</h4>
            <p class="text-muted small mb-0">Informasi lengkap tugas</p>
        </div>
        <div class="d-flex gap-2">
            <?= Html::a('<i class="bi bi-pencil"></i> Update', ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary btn-sm px-3'
            ]) ?>
            <?= Html::a('<i class="bi bi-trash"></i> Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm px-3',
                'data' => ['confirm' => 'Yakin ingin menghapus tugas ini?', 'method' => 'post'],
            ]) ?>
        </div>
    </div>

    <!-- Card Detail Tugas -->
    <div class="card-custom mb-4">
        <table class="table table-custom table-borderless mb-0">
            <tbody>
                <tr>
                    <td class="detail-label">ID</td>
                    <td class="detail-value"><?= $model->id ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Judul Tugas</td>
                    <td class="detail-value fw-medium"><?= Html::encode($model->judul_tugas) ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Dosen</td>
                    <td class="detail-value"><?= Html::encode($model->dosen->nama_dosen ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Mata Kuliah</td>
                    <td class="detail-value"><?= Html::encode($model->matkul->nama_matkul ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Deskripsi</td>
                    <td class="detail-value"><?= nl2br(Html::encode($model->deskripsi)) ?: '<span class="text-muted fst-italic">—</span>' ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Deadline</td>
                    <td class="detail-value">
                        <?= $model->getDeadlineBadge() ?>
                        <?php if ($model->is_holiday): ?>
                            <div class="mt-1">
                                <span class="badge bg-warning text-dark rounded-2">
                                    🎌 Hari Libur: <?= Html::encode($model->holiday_name) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="detail-label">Progress</td>
                    <td class="detail-value"><?= $model->getProgressPengumpulan() ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Status</td>
                    <td class="detail-value">
                        <?php if ($model->isSelesai): ?>
                            <span class="badge-active">Selesai</span>
                        <?php else: ?>
                            <span class="badge-pending">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="detail-label">Created At</td>
                    <td class="detail-value text-muted">
                        <?= $model->created_at
                            ? Yii::$app->formatter->asDatetime($model->created_at, 'dd MMM yyyy, HH:mm')
                            : '—' ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Card Daftar Pengumpulan -->
    <div class="card-custom">
        <div class="p-3 border-bottom" style="border-color: var(--color-card-border) !important;">
            <h6 class="fw-bold mb-0">Daftar Mahasiswa yang Sudah Mengumpulkan</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-borderless mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Waktu Kumpul</th>
                        <th>Status Kumpul</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pengumpulans = \app\models\PengumpulanTugas::find()
                        ->where(['tugas_id' => $model->id])
                        ->orderBy(['waktu_kumpul' => SORT_ASC])
                        ->all();
                    ?>
                    <?php if (empty($pengumpulans)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted fst-italic">
                                Belum ada mahasiswa yang mengumpulkan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($pengumpulans as $i => $p): ?>
                        <tr>
                            <td class="ps-4 py-3 text-muted"><?= $i + 1 ?></td>
                            <td class="py-3 fw-medium">
                                <?= Html::encode($p->mahasiswa->nama_mahasiswa ?? '-') ?>
                            </td>
                            <td class="py-3">
                                <?= Html::encode($p->mahasiswa->nim ?? '-') ?>
                            </td>
                            <td class="py-3 text-muted small">
                                <?= $p->waktu_kumpul
                                    ? Yii::$app->formatter->asDatetime($p->waktu_kumpul, 'dd MMM yyyy, HH:mm')
                                    : '—' ?>
                            </td>
                            <td class="py-3">
                                <?php if ($p->status_kumpul === 'Tepat Waktu'): ?>
                                    <span class="badge bg-success">✓ Tepat Waktu</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">✗ Terlambat</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>