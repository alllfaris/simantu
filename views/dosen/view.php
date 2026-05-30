<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Dosen $model */

$this->title = $model->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Dosen', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="dosen-view">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Detail Dosen</h4>
            <p class="text-muted small mb-0">Informasi lengkap data dosen</p>
        </div>
        <div class="d-flex gap-2">
            <?= Html::a('<i class="bi bi-pencil"></i> Edit', ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary btn-sm px-3'
            ]) ?>
            <?= Html::a('<i class="bi bi-trash"></i> Hapus', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm px-3',
                'data' => ['confirm' => 'Yakin ingin menghapus dosen ini?', 'method' => 'post'],
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
                    <td class="detail-label">Nama Dosen</td>
                    <td class="detail-value fw-medium"><?= Html::encode($model->nama_dosen) ?></td>
                </tr>
                <tr>
                    <td class="detail-label">NIDN</td>
                    <td class="detail-value"><?= Html::encode($model->nidn) ?></td>
                </tr>
                <tr>
                    <td class="detail-label">Email</td>
                    <td class="detail-value"><?= Html::encode($model->email) ?></td>
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