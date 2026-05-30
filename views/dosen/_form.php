<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Dosen $model */
?>

<div class="card-custom mx-auto" style="max-width: 700px;"> 

    <!-- Card Header -->
    <div class="p-4 border-bottom d-flex align-items-center gap-3"
         style="border-color: var(--color-card-border) !important;">
        <div class="d-flex align-items-center justify-content-center rounded-3"
             style="width:48px;height:48px;background:var(--color-primary-pale);">
            <i class="bi bi-person-badge fs-4" style="color:var(--color-primary);"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0"><?= $model->isNewRecord ? 'Tambah Dosen Baru' : 'Edit Data Dosen' ?></h6>
            <p class="text-muted small mb-0">
                <?= $model->isNewRecord ? 'Isi semua field yang diperlukan' : 'Perbarui informasi dosen' ?>
            </p>
        </div>
    </div>

    <!-- Card Body -->
    <div class="p-4">
        <?php $form = ActiveForm::begin([
            'options' => ['novalidate' => true],
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'form-label fw-semibold small mb-1 text-muted'],
                'inputOptions' => [
                    'class' => 'form-control',
                    'style' => 'border-radius:10px; border-color:var(--color-card-border); padding: 10px 14px;',
                ],
                'errorOptions' => ['class' => 'invalid-feedback d-block small'],
            ],
        ]); ?>

        <!-- Nama Dosen — full width -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-person small" style="color:var(--color-primary);"></i>
                <span class="fw-semibold small">Nama Dosen</span>
            </div>
            <?= $form->field($model, 'nama_dosen', ['template' => '{input}{error}'])->textInput([
                'maxlength' => true,
                'placeholder' => 'Contoh: Dr. Budi Santoso, M.Kom.',
                'class' => 'form-control form-control-lg',
                'style' => 'border-radius:12px; border-color:var(--color-card-border); font-size:1rem;',
            ]) ?>
        </div>

        <!-- NIDN & Email — 2 kolom -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-card-text small" style="color:var(--color-primary);"></i>
                    <span class="fw-semibold small">NIDN</span>
                </div>
                <?= $form->field($model, 'nidn', ['template' => '{input}{error}'])->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Contoh: 0012345678',
                    'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                ]) ?>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-envelope small" style="color:var(--color-primary);"></i>
                    <span class="fw-semibold small">Email</span>
                </div>
                <?= $form->field($model, 'email', ['template' => '{input}{error}'])->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Contoh: dosen@gmail.com',
                    'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                ]) ?>
            </div>
        </div>

        <!-- Divider -->
        <hr style="border-color:var(--color-card-border);">

        <!-- Buttons -->
        <div class="d-flex gap-2 justify-content-end mt-3">
            <?= Html::a(
                '<i class="bi bi-arrow-left"></i> Batal',
                ['index'],
                ['class' => 'btn btn-outline-secondary px-4', 'style' => 'border-radius:10px;']
            ) ?>
            <?= Html::submitButton(
                '<i class="bi bi-check-lg"></i> ' . ($model->isNewRecord ? 'Simpan Dosen' : 'Simpan Perubahan'),
                ['class' => 'btn btn-primary px-4 fw-semibold', 'style' => 'border-radius:10px;']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>