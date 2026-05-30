<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\PengumpulanTugas $model */

$this->title = 'Edit Pengumpulan';
$this->params['breadcrumbs'][] = ['label' => 'Pengumpulan Tugas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Detail', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="pengumpulan-tugas-update">
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Pengumpulan Tugas</h4>
        <p class="text-muted small mb-0">Perbarui data pengumpulan tugas mahasiswa</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>