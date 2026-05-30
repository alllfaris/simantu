<?php

use app\models\Mahasiswa;
use app\models\Tugas;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PengumpulanTugas $model */
?>

<div class="card-custom mx-auto" style="max-width: 750px;">

    <!-- Card Header -->
    <div class="p-4 border-bottom d-flex align-items-center gap-3"
         style="border-color: var(--color-card-border) !important;">
        <div class="d-flex align-items-center justify-content-center rounded-3"
             style="width:48px;height:48px;background:var(--color-primary-pale);">
            <i class="bi bi-upload fs-4" style="color:var(--color-primary);"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0"><?= $model->isNewRecord ? 'Tambah Pengumpulan Baru' : 'Edit Pengumpulan' ?></h6>
            <p class="text-muted small mb-0">
                <?= $model->isNewRecord ? 'Isi semua field yang diperlukan' : 'Perbarui data pengumpulan' ?>
            </p>
        </div>
    </div>

    <!-- Card Body -->
    <div class="p-4">
        <?php $form = ActiveForm::begin([
            'options' => [
                'enctype' => 'multipart/form-data',
                'novalidate' => true,
            ],
            'fieldConfig' => [
                'template' => "{input}\n{error}",
                'inputOptions' => [
                    'class' => 'form-control',
                    'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                ],
                'errorOptions' => ['class' => 'invalid-feedback d-block small'],
            ],
        ]); ?>

        <!-- Tugas & Mahasiswa — 2 kolom -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-clipboard-text small" style="color:var(--color-primary);"></i>
                    <span class="fw-semibold small">Tugas</span>
                </div>
                <?= $form->field($model, 'tugas_id')->dropDownList(
                    ArrayHelper::map(Tugas::find()->all(), 'id', 'judul_tugas'),
                    [
                        'prompt' => '— Pilih Tugas —',
                        'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                    ]
                ) ?>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-person small" style="color:var(--color-primary);"></i>
                    <span class="fw-semibold small">Mahasiswa</span>
                </div>
                <?= $form->field($model, 'mahasiswa_id')->dropDownList(
                    ArrayHelper::map(Mahasiswa::find()->all(), 'id', 'nama_mahasiswa'),
                    [
                        'prompt' => '— Pilih Mahasiswa —',
                        'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                    ]
                ) ?>
            </div>
        </div>

        <!-- Upload File -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-file-earmark-arrow-up small" style="color:var(--color-primary);"></i>
                <span class="fw-semibold small">Upload File (PDF/ZIP)</span>
            </div>
            <?= $form->field($model, 'uploadFile')->fileInput([
                'class' => 'form-control',
                'accept' => '.pdf,.zip',
                'style' => 'border-radius:10px; border-color:var(--color-card-border);',
            ]) ?>
            <?php if ($model->file_tugas): ?>
                <div class="mt-2">
                    <small class="text-muted">File saat ini: </small>
                    <?= Html::a(
                        '<i class="bi bi-file-earmark-text"></i> ' . basename($model->file_tugas),
                        Yii::getAlias('@web/' . $model->file_tugas),
                        ['class' => 'small text-primary', 'target' => '_blank']
                    ) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Link Drive -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-link-45deg small" style="color:var(--color-primary);"></i>
                <span class="fw-semibold small">Link Google Drive (opsional)</span>
            </div>
            <?= $form->field($model, 'link_tugas')->textInput([
                'placeholder' => 'https://drive.google.com/...',
                'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
            ]) ?>
        </div>

        <!-- Catatan -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-chat-text small" style="color:var(--color-primary);"></i>
                <span class="fw-semibold small">Catatan (opsional)</span>
            </div>
            <?= $form->field($model, 'catatan')->textarea([
                'rows' => 4,
                'placeholder' => 'Catatan tambahan untuk dosen...',
                'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px; resize:vertical;',
            ]) ?>
        </div>

        <hr style="border-color:var(--color-card-border);">

        <div class="d-flex gap-2 justify-content-end mt-3">
            <?= Html::a('<i class="bi bi-arrow-left"></i> Batal', ['index'],
                ['class' => 'btn btn-outline-secondary px-4', 'style' => 'border-radius:10px;']
            ) ?>
            <?= Html::submitButton(
                '<i class="bi bi-check-lg"></i> ' . ($model->isNewRecord ? 'Simpan' : 'Simpan Perubahan'),
                ['class' => 'btn btn-primary px-4 fw-semibold', 'style' => 'border-radius:10px;']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>