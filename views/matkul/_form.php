<?php

use app\models\Dosen;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var app\models\Matkul $model */
?>

<div class="card-custom mx-auto" style="max-width: 700px;">

    <div class="p-4 border-bottom d-flex align-items-center gap-3"
         style="border-color: var(--color-card-border) !important;">
        <div class="d-flex align-items-center justify-content-center rounded-3"
             style="width:48px;height:48px;background:var(--color-primary-pale);">
            <i class="bi bi-book fs-4" style="color:var(--color-primary);"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0"><?= $model->isNewRecord ? 'Tambah Mata Kuliah Baru' : 'Edit Mata Kuliah' ?></h6>
            <p class="text-muted small mb-0">
                <?= $model->isNewRecord ? 'Isi semua field yang diperlukan' : 'Perbarui informasi mata kuliah' ?>
            </p>
        </div>
    </div>

    <div class="p-4">
        <?php $form = ActiveForm::begin([
            'options' => ['novalidate' => true],
            'fieldConfig' => [
                'template' => "{input}\n{error}",
                'inputOptions' => [
                    'class' => 'form-control',
                    'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                ],
                'errorOptions' => ['class' => 'invalid-feedback d-block small'],
            ],
        ]); ?>

        <!-- Nama Matkul — full width -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-journal-text small" style="color:var(--color-primary);"></i>
                <span class="fw-semibold small">Nama Mata Kuliah</span>
            </div>
            <?= $form->field($model, 'nama_matkul')->textInput([
                'maxlength' => true,
                'placeholder' => 'Contoh: Pemrograman Berbasis Platform',
                'class' => 'form-control form-control-lg',
                'style' => 'border-radius:12px; border-color:var(--color-card-border);',
            ]) ?>
        </div>

        <!-- Kode & Semester — 2 kolom -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-upc small" style="color:var(--color-primary);"></i>
                    <span class="fw-semibold small">Kode Matkul</span>
                </div>
                <?= $form->field($model, 'kode_matkul')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'Contoh: PBP101',
                ]) ?>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-calendar3 small" style="color:var(--color-primary);"></i>
                    <span class="fw-semibold small">Semester</span>
                </div>
                <?= $form->field($model, 'semester')->textInput([
                    'placeholder' => 'Contoh: 5',
                ]) ?>
            </div>
        </div>

        <!-- Dosen — full width -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-person-badge small" style="color:var(--color-primary);"></i>
                <span class="fw-semibold small">Dosen Pengampu</span>
            </div>
            <?= $form->field($model, 'dosen_id')->dropDownList(
                ArrayHelper::map(Dosen::find()->all(), 'id', 'nama_dosen'),
                [
                    'prompt' => '— Pilih Dosen —',
                    'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                ]
            ) ?>
        </div>

        <hr style="border-color:var(--color-card-border);">

        <div class="d-flex gap-2 justify-content-end mt-3">
            <?= Html::a('<i class="bi bi-arrow-left"></i> Batal', ['index'],
                ['class' => 'btn btn-outline-secondary px-4', 'style' => 'border-radius:10px;']
            ) ?>
            <?= Html::submitButton(
                '<i class="bi bi-check-lg"></i> ' . ($model->isNewRecord ? 'Simpan Matkul' : 'Simpan Perubahan'),
                ['class' => 'btn btn-primary px-4 fw-semibold', 'style' => 'border-radius:10px;']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>