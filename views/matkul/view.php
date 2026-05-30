<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Matkul $model */

$this->title = $model->nama_matkul;
$this->params['breadcrumbs'][] = ['label' => 'Mata Kuliah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="matkul-view">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Detail Mata Kuliah</h4>
            <p class="text-muted small mb-0">Informasi lengkap data mata kuliah</p>
        </div>
        <div class="d-flex gap-2">
            <?= Html::a('<i class="bi bi-pencil"></i> Edit', ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary btn-sm px-3'
            ]) ?>
            <?= Html::a('<i class="bi bi-trash"></i> Hapus', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm px-3',
                'data' => ['confirm' => 'Yakin ingin menghapus mata kuliah ini?', 'method' => 'post'],
            ]) ?>
        </div>
    </div>

    <div class="card-custom">
        <table class="table table-custom table-borderless mb-0">
            <tbody>
                <tr>
                    <td class="detail-label">ID</td>
                    <td class="detail-value"><?= $model->id ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Nama Mata Kuliah</td>
                    <td class="detail-value fw-medium"><?= Html::encode($model->nama_matkul) ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Kode Matkul</td>
                    <td class="detail-value">
                        <span class="badge bg-light text-dark border px-3"><?= Html::encode($model->kode_matkul) ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="detail-label">Dosen Pengampu</td>
                    <td class="detail-value"><?= Html::encode($model->dosen->nama_dosen ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Semester</td>
                    <td class="detail-value"><?= Html::encode($model->semester ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Dibuat</td>
                    <td class="detail-value text-muted">
                        <?= $model->created_at
                            ? Yii::$app->formatter->asDatetime($model->created_at, 'dd MMM yyyy, HH:mm')
                            : '—' ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>