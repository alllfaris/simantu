<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Tugas $tugas */
/** @var app\models\Mahasiswa $mahasiswa */
/** @var app\models\PengumpulanTugas|null $pengumpulan */

$this->title = $tugas->judul_tugas;
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/mahasiswa/dashboard']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mahasiswa-tugas-detail">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Detail Tugas</h4>
        <p class="text-muted small mb-0">Informasi tugas dan form pengumpulan</p>
    </div>

    <!-- Card Info Tugas -->
    <div class="card-custom mb-4">
        <table class="table table-custom table-borderless mb-0">
            <tbody>
                <tr>
                    <td class="detail-label">Judul Tugas</td>
                    <td class="detail-value fw-medium"><?= Html::encode($tugas->judul_tugas) ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Dosen</td>
                    <td class="detail-value"><?= Html::encode($tugas->dosen->nama_dosen ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Mata Kuliah</td>
                    <td class="detail-value"><?= Html::encode($tugas->matkul->nama_matkul ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Deskripsi</td>
                    <td class="detail-value"><?= nl2br(Html::encode($tugas->deskripsi)) ?: '<span class="text-muted fst-italic">—</span>' ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Deadline</td>
                    <td class="detail-value">
                        <?= $tugas->getDeadlineBadge() ?>
                        <?php if ($tugas->is_holiday): ?>
                            <div class="mt-1">
                                <span class="badge bg-warning text-dark rounded-2">
                                    🎌 Hari Libur: <?= Html::encode($tugas->holiday_name) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if ($pengumpulan): ?>
        <!-- Sudah dikumpulkan -->
        <div class="card-custom p-4">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle"
                     style="width:48px;height:48px;background:var(--color-primary-pale);">
                    <i class="bi bi-check-circle-fill" style="color:var(--color-success);font-size:1.5rem;"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Tugas Sudah Dikumpulkan</h6>
                    <p class="text-muted small mb-0">Kamu sudah mengumpulkan tugas ini</p>
                </div>
            </div>
            <table class="table table-custom table-borderless mb-0">
                <tbody>
                    <tr>
                        <td class="detail-label">Waktu Kumpul</td>
                        <td class="detail-value">
                            <?= Yii::$app->formatter->asDatetime($pengumpulan->waktu_kumpul, 'dd MMM yyyy, HH:mm') ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="detail-label">Status</td>
                        <td class="detail-value">
                            <?php if ($pengumpulan->status_kumpul === 'Tepat Waktu'): ?>
                                <span class="badge bg-success">✓ Tepat Waktu</span>
                            <?php else: ?>
                                <span class="badge bg-danger">✗ Terlambat</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($pengumpulan->file_tugas): ?>
                    <tr>
                        <td class="detail-label">File</td>
                        <td class="detail-value">
                            <?= Html::a('📄 Download File',
                                Yii::getAlias('@web/' . $pengumpulan->file_tugas),
                                ['class' => 'btn btn-outline-primary btn-sm', 'target' => '_blank']
                            ) ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($pengumpulan->link_tugas): ?>
                    <tr>
                        <td class="detail-label">Link Drive</td>
                        <td class="detail-value">
                            <?= Html::a('🔗 Buka Link', $pengumpulan->link_tugas, [
                                'class' => 'btn btn-outline-secondary btn-sm',
                                'target' => '_blank',
                            ]) ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($pengumpulan->catatan): ?>
                    <tr>
                        <td class="detail-label">Catatan</td>
                        <td class="detail-value"><?= Html::encode($pengumpulan->catatan) ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    <?php else: ?>
        <!-- Form Pengumpulan -->
        <div class="card-custom p-4">
            <h6 class="fw-bold mb-3">Form Pengumpulan Tugas</h6>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert alert-danger"><?= Yii::$app->session->getFlash('error') ?></div>
            <?php endif; ?>

            <?php $form = ActiveForm::begin([
                'action' => ['/mahasiswa/kumpul-tugas', 'id' => $tugas->id],
                'options' => ['enctype' => 'multipart/form-data'],
            ]); ?>

            <?= $form->field(new \app\models\PengumpulanTugas(), 'uploadFile', [
                'template' => '{label}{input}{error}',
            ])->fileInput([
                'class' => 'form-control',
                'accept' => '.pdf,.zip',
            ])->label('Upload File (PDF/ZIP)', ['class' => 'form-label fw-semibold small']) ?>

            <?= $form->field(new \app\models\PengumpulanTugas(), 'link_tugas', [
                'template' => '{label}{input}{error}',
            ])->textInput([
                'class' => 'form-control',
                'placeholder' => 'https://drive.google.com/...',
                'style' => 'border-radius:10px;',
            ])->label('Link Google Drive (opsional)', ['class' => 'form-label fw-semibold small']) ?>

            <?= $form->field(new \app\models\PengumpulanTugas(), 'catatan', [
                'template' => '{label}{input}{error}',
            ])->textarea([
                'rows' => 3,
                'class' => 'form-control',
                'placeholder' => 'Catatan tambahan (opsional)',
                'style' => 'border-radius:10px;',
            ])->label('Catatan', ['class' => 'form-label fw-semibold small']) ?>

            <div class="d-grid mt-3">
                <?= Html::submitButton('<i class="bi bi-send"></i> Kirim Tugas', [
                    'class' => 'btn btn-primary py-2 fw-semibold',
                    'style' => 'border-radius:10px;',
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    <?php endif; ?>

</div>