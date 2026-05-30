<?php

use yii\helpers\Html;
/** @var app\models\Matkul $model */

$this->title = 'Tambah Mata Kuliah';
$this->params['breadcrumbs'][] = ['label' => 'Mata Kuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="matkul-create">
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Tambah Mata Kuliah</h4>
        <p class="text-muted small mb-0">Isi form berikut untuk menambahkan mata kuliah baru</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>