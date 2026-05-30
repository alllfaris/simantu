<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Tugas $model */

$this->title = 'Tambah Tugas';
$this->params['breadcrumbs'][] = ['label' => 'Tugas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tugas-create">
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Tambah Tugas</h4>
        <p class="text-muted small mb-0">Isi form berikut untuk menambahkan tugas baru</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>