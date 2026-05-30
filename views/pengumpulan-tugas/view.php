<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PengumpulanTugas $model */

$this->title = 'Detail Pengumpulan';
$this->params['breadcrumbs'][] = ['label' => 'Pengumpulan Tugas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pengumpulan-tugas-view">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Detail Pengumpulan</h4>
            <p class="text-muted small mb-0">Informasi lengkap pengumpulan tugas mahasiswa</p>
        </div>
        <div class="d-flex gap-2">
            <?= Html::a('<i class="bi bi-pencil"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm px-3']) ?>
            <?= Html::a('<i class="bi bi-trash"></i> Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm px-3',
                'data' => ['confirm' => 'Yakin ingin menghapus data ini?', 'method' => 'post'],
            ]) ?>
        </div>
    </div>

    <!-- Card Detail -->
    <div class="card-custom">
        <table class="table table-custom table-borderless mb-0">
            <tbody>

                <tr>
                    <td class="detail-label">Judul Tugas</td>
                    <td class="detail-value fw-medium"><?= Html::encode($model->tugas->judul_tugas) ?></td>
                </tr>

                <tr>
                    <td class="detail-label">Nama Mahasiswa</td>
                    <td class="detail-value"><?= Html::encode($model->mahasiswa->nama_mahasiswa) ?></td>
                </tr>

                <tr>
                    <td class="detail-label">File Tugas</td>
                    <td class="detail-value">
                        <?php
                        if (!$model->file_tugas) {
                            echo '<span class="text-muted fst-italic">Tidak ada file</span>';
                        } elseif (!file_exists(Yii::getAlias('@webroot/' . $model->file_tugas))) {
                            echo '<span class="text-danger">File tidak ditemukan</span>';
                        } else {
                            echo Html::a(
                                '📄 Download File',
                                Yii::getAlias('@web/' . $model->file_tugas),
                                ['class' => 'btn btn-outline-primary btn-sm', 'target' => '_blank']
                            );
                        }
                        ?>
                    </td>
                </tr>

                <tr>
                    <td class="detail-label">Catatan</td>
                    <td class="detail-value"><?= nl2br(Html::encode($model->catatan)) ?: '<span class="text-muted fst-italic">—</span>' ?></td>
                </tr>

                <tr>
                    <td class="detail-label">Status Kumpul</td>
                    <td class="detail-value">
                        <?php if ($model->isStatusTepatWaktu()): ?>
                            <span class="badge bg-success">✓ Tepat Waktu</span>
                        <?php else: ?>
                            <span class="badge bg-danger">✗ Terlambat</span>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td class="detail-label">Waktu Kumpul</td>
                    <td class="detail-value"><?= Yii::$app->formatter->asDatetime($model->waktu_kumpul, 'dd MMM yyyy, HH:mm') ?></td>
                </tr>

                <tr>
                    <td class="detail-label">Created At</td>
                    <td class="detail-value text-muted"><?= Yii::$app->formatter->asDatetime($model->created_at, 'dd MMM yyyy, HH:mm') ?></td>
                </tr>

            </tbody>
        </table>
    </div>

</div>