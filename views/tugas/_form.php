<?php

use app\models\Dosen;
use app\models\Matkul;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Tugas $model */
?>

<div class="card-custom mx-auto" style="max-width: 750px;">

    <!-- Card Header -->
    <div class="p-4 border-bottom d-flex align-items-center gap-3"
         style="border-color: var(--color-card-border) !important;">
        <div class="d-flex align-items-center justify-content-center rounded-3"
             style="width:48px;height:48px;background:var(--color-primary-pale);">
            <i class="bi bi-clipboard-text fs-4" style="color:var(--color-primary);"></i>
        </div>
        <div>
            <h6 class="fw-bold mb-0"><?= $model->isNewRecord ? 'Tambah Tugas Baru' : 'Edit Tugas' ?></h6>
            <p class="text-muted small mb-0">
                <?= $model->isNewRecord ? 'Isi semua field yang diperlukan' : 'Perbarui informasi tugas' ?>
            </p>
        </div>
    </div>

    <!-- Card Body -->
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

        <!-- Judul Tugas — full width -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-pencil-square small" style="color:var(--color-primary);"></i>
                <span class="fw-semibold small">Judul Tugas</span>
            </div>
            <?= $form->field($model, 'judul_tugas')->textInput([
                'maxlength' => true,
                'placeholder' => 'Contoh: Buat ERD dan Use Case',
                'class' => 'form-control form-control-lg',
                'style' => 'border-radius:12px; border-color:var(--color-card-border);',
            ]) ?>
        </div>

        <!-- Dosen & Matkul — 2 kolom -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
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
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-book small" style="color:var(--color-primary);"></i>
                    <span class="fw-semibold small">Mata Kuliah</span>
                </div>
                <?= $form->field($model, 'matkul_id')->dropDownList(
                    ArrayHelper::map(Matkul::find()->all(), 'id', 'nama_matkul'),
                    [
                        'prompt' => '— Pilih Matkul —',
                        'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                    ]
                ) ?>
            </div>
        </div>

        <!-- Deadline & Status — 2 kolom -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-calendar-event small" style="color:var(--color-primary);"></i>
                    <span class="fw-semibold small">Deadline</span>
                </div>
                <?= $form->field($model, 'deadline')->input('date', [
                    'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                ]) ?>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-toggle-on small" style="color:var(--color-primary);"></i>
                    <span class="fw-semibold small">Status</span>
                </div>
                <?= $form->field($model, 'status')->dropDownList(
                    ['aktif' => 'Aktif', 'selesai' => 'Selesai'],
                    [
                        'prompt' => '— Pilih Status —',
                        'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px;',
                    ]
                ) ?>
            </div>
        </div>

        <!-- Deskripsi — full width -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-text-paragraph small" style="color:var(--color-primary);"></i>
                <span class="fw-semibold small">Deskripsi Tugas</span>
            </div>
            <?= $form->field($model, 'deskripsi')->textarea([
                'rows' => 5,
                'placeholder' => 'Tuliskan instruksi atau deskripsi tugas secara lengkap...',
                'style' => 'border-radius:10px; border-color:var(--color-card-border); padding:10px 14px; resize:vertical;',
            ]) ?>
        </div>

        <hr style="border-color:var(--color-card-border);">

        <div class="d-flex gap-2 justify-content-end mt-3">
            <?= Html::a('<i class="bi bi-arrow-left"></i> Batal', ['index'],
                ['class' => 'btn btn-outline-secondary px-4', 'style' => 'border-radius:10px;']
            ) ?>
            <?= Html::submitButton(
                '<i class="bi bi-check-lg"></i> ' . ($model->isNewRecord ? 'Simpan Tugas' : 'Simpan Perubahan'),
                ['class' => 'btn btn-primary px-4 fw-semibold', 'style' => 'border-radius:10px;']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>