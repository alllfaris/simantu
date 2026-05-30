<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Dosen $model */

$this->title = 'Edit Dosen: ' . $model->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nama_dosen, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="dosen-update">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Edit Dosen</h4>
        <p class="text-muted small mb-0">Perbarui data dosen</p>
    </div>

    <?= $this->render('_form', ['model' => $model]) ?>

</div>