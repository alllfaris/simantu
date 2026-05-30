<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Tugas $model */

$this->title = 'Edit Tugas: ' . $model->judul_tugas;
$this->params['breadcrumbs'][] = ['label' => 'Tugas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->judul_tugas, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="tugas-update">
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Tugas</h4>
        <p class="text-muted small mb-0">Perbarui data tugas</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>