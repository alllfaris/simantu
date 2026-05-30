<?php

use yii\helpers\Html;
/** @var app\models\Matkul $model */

$this->title = 'Edit Mata Kuliah: ' . $model->nama_matkul;
$this->params['breadcrumbs'][] = ['label' => 'Mata Kuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_matkul, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="matkul-update">
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Mata Kuliah</h4>
        <p class="text-muted small mb-0">Perbarui data mata kuliah</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>