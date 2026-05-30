<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var string $content */

use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\helpers\Html;

$this->render('_head');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="light">
<head>

    <?php $this->head() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<div class="d-flex">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar p-3" id="sidebar">
        <h4 class="mb-4 fw-bold text-success">
            SiManTu
        </h4>

        <?php
            $role = Yii::$app->user->identity->role ?? null;
        ?>

       <?php use yii\helpers\Url; ?>

        <ul class="nav flex-column gap-2">

        <?php if ($role === 'admin'): ?>

            <li class="nav-item">
                <a class="nav-link sidebar-link" href="<?= Url::to(['/site/index']) ?>">
                    Dashboard Admin
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link sidebar-link" href="<?= Url::to(['/dosen/index']) ?>">
                    Dosen
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link sidebar-link" href="<?= Url::to(['/matkul/index']) ?>">
                    Mata Kuliah
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link sidebar-link" href="<?= Url::to(['/tugas/index']) ?>">
                    Tugas
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link sidebar-link" href="<?= Url::to(['/pengumpulan-tugas/index']) ?>">
                    Pengumpulan Tugas
                </a>
            </li>

        <?php elseif ($role === 'mahasiswa'): ?>

            <li class="nav-item">
                <a class="nav-link sidebar-link" href="<?= Url::to(['/mahasiswa/dashboard']) ?>">
                    Dashboard Mahasiswa
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link sidebar-link" href="<?= Url::to(['/mahasiswa/daftar-tugas']) ?>">
                    Daftar Tugas Saya
                </a>
            </li>

        <?php endif; ?>

        </ul>
    </aside>

    <main id="main" class="flex-grow-1" role="main">

        <header class="topbar px-4 py-3 d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center gap-3">

                <button class="toggle-sidebar-btn" id="toggleSidebar">
                    ☰
                </button>

                <h5 class="mb-0 fw-bold">
                    Dashboard
                </h5>

            </div>
            
            <div class="dropdown">

                <a href="#"
                class="d-flex align-items-center text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown">

                    <img
                        src="https://ui-avatars.com/api/?name=<?= urlencode(Yii::$app->user->isGuest ? 'User' : Yii::$app->user->identity->username) ?>&background=0F6E56&color=fff"
                        width="40"
                        height="40"
                        class="rounded-circle"
                    >

                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                    <li>
                        <a class="dropdown-item" href="#">
                            👤 Profile
                        </a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="#">
                            ⚙ Settings
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <?= Html::a('<i class="bi bi-door-open"></i> Logout', ['/site/logout'], [
                            'data-method' => 'post',
                            'class' => 'dropdown-item text-danger',
                        ]) ?>
                    </li>

                </ul>

            </div>

        </header>
        <div class="container-fluid p-4">

            <?php if (!empty($this->params['breadcrumbs'])): ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>

            <?= Alert::widget() ?>

            <?= $content ?>

        </div>
    </main>

</div>

<footer class="text-center py-3 text-muted small">
    © <?= date('Y') ?> SiManTu — Sistem Manajemen Tugas
</footer>

<?php $this->endBody() ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $this->endPage() ?>
