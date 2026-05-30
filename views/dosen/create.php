<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Dosen $model */

$this->title = 'Tambah Dosen';
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dosen-create">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Tambah Dosen</h4>
        <p class="text-muted small mb-0">Isi form berikut untuk menambahkan dosen baru</p>
    </div>

    <?= $this->render('_form', ['model' => $model]) ?>

</div>