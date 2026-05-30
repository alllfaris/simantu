<?php

use app\models\PengumpulanTugas;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\PengumpulanTugasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pengumpulan Tugas';
$this->params['breadcrumbs'][] = $this->title;

$models = $dataProvider->getModels();
?>

<div class="pengumpulan-tugas-index">

    <!-- Header -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Pengumpulan Tugas</h4>
        <p class="text-muted small mb-0">Kelola data pengumpulan tugas mahasiswa.</p>
    </div>

    <!-- Toolbar -->
    <div class="card-custom p-3 mb-3">
        <?= Html::beginForm(['index'], 'get', ['class' => 'd-flex gap-2 flex-wrap']) ?>
            <div class="input-search-wrapper">
                <i class="bi bi-search input-search-icon"></i>
                <?= Html::input('text', 'PengumpulanTugasSearch[nama_mahasiswa]', 
                    $searchModel->nama_mahasiswa, [
                    'class' => 'form-control input-search',
                    'placeholder' => 'Cari mahasiswa...',
                ]) ?>
            </div>
        <?= Html::endForm() ?>
    </div>

    <!-- Table -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-custom table-borderless mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Judul Tugas</th>
                        <th>Mahasiswa</th>
                        <th class="d-none d-md-table-cell">File</th>
                        <th class="d-none d-lg-table-cell">Waktu Kumpul</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($models)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Data pengumpulan tidak ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($models as $i => $model): ?>
                        <tr>
                            <td class="ps-4 py-3 text-muted">
                                <?= $dataProvider->pagination->offset + $i + 1 ?>
                            </td>

                            <td class="py-3 fw-medium">
                                <?= Html::encode($model->tugas->judul_tugas ?? '-') ?>
                            </td>

                            <td class="py-3">
                                <?= Html::encode($model->mahasiswa->nama_mahasiswa ?? '-') ?>
                            </td>

                            <td class="py-3 d-none d-md-table-cell">
                                <?php if ($model->file_tugas && file_exists(Yii::getAlias('@webroot/' . $model->file_tugas))): ?>
                                    <?= Html::a('<i class="bi bi-file-earmark-arrow-down"></i> Download File',
                                        Yii::getAlias('@web/' . $model->file_tugas),
                                        ['class' => 'btn btn-outline-primary btn-sm', 'target' => '_blank']
                                    ) ?>
                                <?php elseif ($model->link_tugas): ?>
                                    <?= Html::a('<i class="bi bi-box-arrow-up-right"></i> Buka Drive',
                                        $model->link_tugas,
                                        ['class' => 'btn btn-outline-success btn-sm', 'target' => '_blank', 'rel' => 'noopener noreferrer']
                                    ) ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic small">—</span>
                                <?php endif; ?>
                            </td>

                            <td class="py-3 d-none d-lg-table-cell text-muted small">
                                <?= $model->waktu_kumpul
                                    ? Yii::$app->formatter->asDatetime($model->waktu_kumpul, 'dd MMM yyyy, HH:mm')
                                    : '—' ?>
                            </td>

                            <td class="py-3">
                                <?php if ($model->status_kumpul === 'Tepat Waktu'): ?>
                                    <span class="badge-active">Tepat Waktu</span>
                                <?php elseif ($model->status_kumpul === 'Terlambat'): ?>
                                    <span class="badge-danger">Terlambat</span>
                                <?php else: ?>
                                    <span class="badge-pending">—</span>
                                <?php endif; ?>
                            </td>

                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <?= Html::a('<i class="bi bi-eye"></i>',
                                        ['view', 'id' => $model->id],
                                        ['class' => 'btn btn-sm btn-light border rounded-3', 'title' => 'Detail']
                                    ) ?>
                                    <?= Html::a('<i class="bi bi-pencil"></i>',
                                        ['update', 'id' => $model->id],
                                        ['class' => 'btn btn-sm btn-light border rounded-3', 'title' => 'Edit']
                                    ) ?>
                                    <?= Html::a('<i class="bi bi-trash"></i>',
                                        ['delete', 'id' => $model->id],
                                        [
                                            'class' => 'btn btn-sm btn-light border rounded-3 text-danger',
                                            'title' => 'Hapus',
                                            'data' => ['confirm' => 'Yakin ingin menghapus?', 'method' => 'post'],
                                        ]
                                    ) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-end mt-3">
        <?= \yii\widgets\LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'options' => ['class' => 'pagination gap-1 mb-0'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link rounded-3 border-0'],
            'activePageCssClass' => 'active',
            'disabledPageCssClass' => 'disabled',
            'prevPageLabel' => '<i class="bi bi-chevron-left"></i>',
            'nextPageLabel' => '<i class="bi bi-chevron-right"></i>',
        ]) ?>
    </div>

</div>