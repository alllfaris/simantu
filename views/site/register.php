<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\RegisterForm $model */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Registrasi Mahasiswa';
?>

<div class="site-register min-vh-100 d-flex align-items-center justify-content-center p-4" 
     style="background: var(--color-page-bg);">

    <div style="width: 100%; max-width: 500px;">

        <!-- Brand -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
                 style="width:64px;height:64px;background:var(--color-primary);">
                <i class="bi bi-person-plus-fill text-white fs-2"></i>
            </div>
            <h1 class="fw-bold mb-1" style="color:var(--color-primary);font-size:1.8rem;">Daftar Akun</h1>
            <p class="text-muted small">Buat akun untuk mengakses Portal Akademik</p>
        </div>

        <!-- Card -->
        <div class="card-custom p-4">

            <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'options' => ['novalidate' => true],
                'fieldConfig' => ['template' => "{label}\n{input}\n{error}"],
            ]); ?>

            <h6 class="fw-bold mb-3">Informasi Akun</h6>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'username', [
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Username',
                            'style' => 'border-radius:10px; border-color:var(--color-card-border);',
                        ],
                    ])->label('Username', ['class' => 'form-label fw-semibold small mb-1']) ?>
                </div>

                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'email', [
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Email',
                            'style' => 'border-radius:10px; border-color:var(--color-card-border);',
                        ],
                    ])->label('Email', ['class' => 'form-label fw-semibold small mb-1']) ?>
                </div>
            </div>

            <div class="mb-4">
                <?= $form->field($model, 'password', [
                    'inputOptions' => [
                        'class' => 'form-control ps-5',
                        'placeholder' => '••••••••',
                        'style' => 'border-radius:10px; border-color:var(--color-card-border);',
                        'id' => 'password-input',
                    ],
                    'template' => '
                        {label}
                        <div class="position-relative">
                            <i class="bi bi-lock position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                            {input}
                            <button type="button" onclick="togglePassword()" 
                                class="btn btn-sm position-absolute top-50 translate-middle-y end-0 me-2 text-muted border-0 bg-transparent">
                                <i class="bi bi-eye" id="pass-icon"></i>
                            </button>
                        </div> 
                        {error}
                    ',
                ])->passwordInput()->label('Password', ['class' => 'form-label fw-semibold small mb-1']) ?>
            </div>

            <hr style="border-color:var(--color-card-border);">
            <h6 class="fw-bold mb-3 mt-4">Profil Mahasiswa</h6>

            <div class="mb-3">
                <?= $form->field($model, 'nama_mahasiswa', [
                    'inputOptions' => [
                        'class' => 'form-control',
                        'placeholder' => 'Nama Lengkap Mahasiswa',
                        'style' => 'border-radius:10px; border-color:var(--color-card-border);',
                    ],
                ])->label('Nama Lengkap', ['class' => 'form-label fw-semibold small mb-1']) ?>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'nim', [
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'NIM',
                            'style' => 'border-radius:10px; border-color:var(--color-card-border);',
                        ],
                    ])->label('NIM', ['class' => 'form-label fw-semibold small mb-1']) ?>
                </div>

                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'kelas', [
                        'inputOptions' => [
                            'class' => 'form-control',
                            'placeholder' => 'Masukkan Kelas',
                            'style' => 'border-radius:10px; border-color:var(--color-card-border);',
                        ],
                    ])->label('Kelas', ['class' => 'form-label fw-semibold small mb-1']) ?>
                </div> 
            </div>

            <!-- Submit -->
            <div class="d-grid mt-4 mb-3">
                <?= Html::submitButton('Daftar Sekarang', [
                    'class' => 'btn btn-primary py-2 fw-semibold',
                    'style' => 'border-radius:10px;',
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <!-- Divider -->
            <div class="d-flex align-items-center gap-3 my-3">
                <hr class="flex-grow-1 m-0" style="border-color:var(--color-card-border);">
                <span class="text-muted small">ATAU</span>
                <hr class="flex-grow-1 m-0" style="border-color:var(--color-card-border);">
            </div>

            <!-- Login link -->
            <p class="text-center text-muted small mb-0">
                Sudah punya akun?
                <?= Html::a('Login di sini', ['/site/login'], ['class' => 'fw-bold text-decoration-none', 'style' => 'color:var(--color-primary);']) ?>
            </p>

        </div>

    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password-input');
    const icon  = document.getElementById('pass-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
