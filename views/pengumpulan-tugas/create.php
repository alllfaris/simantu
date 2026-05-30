<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PengumpulanTugas $model */

$this->title = 'Tambah Pengumpulan Tugas';
$this->params['breadcrumbs'][] = ['label' => 'Pengumpulan Tugas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pengumpulan-tugas-create">
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Tambah Pengumpulan Tugas</h4>
        <p class="text-muted small mb-0">Isi form berikut untuk menambahkan data pengumpulan</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>