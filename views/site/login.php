<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'Login';
?>

<div class="site-login min-vh-100 d-flex align-items-center justify-content-center p-4" 
     style="background: var(--color-page-bg);">

    <div style="width: 100%; max-width: 440px;">

        <!-- Brand -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
                 style="width:64px;height:64px;background:var(--color-primary);">
                <i class="bi bi-mortarboard-fill text-white fs-2"></i>
            </div>
            <h1 class="fw-bold mb-1" style="color:var(--color-primary);font-size:1.8rem;">SiManTu</h1>
            <p class="text-muted small">Selamat datang kembali di Portal Akademik</p>
        </div>

        <!-- Card -->
        <div class="card-custom p-4">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['novalidate' => true],
                'fieldConfig' => ['template' => "{label}\n{input}\n{error}"],
            ]); ?>

            <!-- Username -->
            <div class="mb-3">
                <?= $form->field($model, 'username', [
                    'inputOptions' => [
                        'class' => 'form-control ps-5',
                        'placeholder' => 'Masukkan username',
                        'style' => 'border-radius:10px; border-color:var(--color-card-border);',
                    ],
                    'template' => '
                        {label}
                        <div class="position-relative">
                            <i class="bi bi-person position-absolute top-50 translate-middle-y ms-3 text-muted"></i>
                            {input}
                        </div>
                        {error}
                    ',
                ])->label('Username', ['class' => 'form-label fw-semibold small mb-1']) ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
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

            <!-- Remember Me -->
            <div class="mb-4">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'label' => 'Ingat saya',
                    'labelOptions' => ['class' => 'form-check-label small'],
                ]) ?>
            </div>

            <!-- Submit -->
            <div class="d-grid mb-3">
                <?= Html::submitButton('Login', [
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

            <!-- Register link -->
            <p class="text-center text-muted small mb-0">
                Belum punya akun?
                <?= Html::a('Daftar di sini', ['/site/register'], ['class' => 'fw-bold text-decoration-none', 'style' => 'color:var(--color-primary);']) ?>
            </p>

        </div>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="text-muted small mb-1">SiManTu — Sistem Manajemen Tugas</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#" class="text-muted small text-decoration-none">Panduan Pengguna</a>
                <span class="text-muted">•</span>
                <a href="#" class="text-muted small text-decoration-none">Bantuan Teknis</a>
            </div>
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