<?php

use app\models\Tugas;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\TugasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tugas';
$this->params['breadcrumbs'][] = $this->title;

$models = $dataProvider->getModels();
?>

<div class="tugas-index">

    <!-- Header -->
    <div class="mb-4">
        <h4 class="fw-bold mb-1">Manajemen Tugas</h4>
        <p class="text-muted small mb-0">Kelola daftar tugas mahasiswa.</p>
    </div>

    <!-- Toolbar -->
    <div class="card-custom p-3 mb-3 d-flex justify-content-between align-items-center gap-3">
        <div class="d-flex gap-2 flex-wrap">
            <?php $form = \yii\widgets\ActiveForm::begin(['method' => 'get', 'options' => ['class' => 'd-flex gap-2 flex-wrap']]); ?>
                <div class="input-search-wrapper">
                    <i class="bi bi-search input-search-icon"></i>
                    <?= $form->field($searchModel, 'judul_tugas', [
                        'template' => '{input}',
                        'inputOptions' => ['class' => 'form-control input-search', 'placeholder' => 'Cari judul tugas...']
                    ]) ?>
                </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
        <?= Html::a('<i class="bi bi-plus-lg"></i> Tambah Tugas', ['create'], [
            'class' => 'btn btn-primary px-4'
        ]) ?>
    </div>

    <!-- Table -->
    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-custom table-borderless mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Judul Tugas</th>
                        <th class="d-none d-md-table-cell">Dosen</th>
                        <th class="d-none d-md-table-cell">Mata Kuliah</th>
                        <th class="d-none d-lg-table-cell">Deadline</th>
                        <th class="d-none d-lg-table-cell">Progress</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr> 
                </thead>
                <tbody>
                    <?php if (empty($models)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Data tugas tidak ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($models as $i => $model): ?>
                        <tr>
                            <td class="ps-4 py-3 text-muted">
                                <?= $dataProvider->pagination->offset + $i + 1 ?>
                            </td>

                            <td class="py-3 fw-medium">
                                <?= Html::encode($model->judul_tugas) ?>
                            </td>

                            <td class="py-3 d-none d-md-table-cell">
                                <?= Html::encode($model->dosen->nama_dosen ?? '-') ?>
                            </td>

                            <td class="py-3 d-none d-md-table-cell">
                                <?= Html::encode($model->matkul->nama_matkul ?? '-') ?>
                            </td>

                            <td class="py-3 d-none d-lg-table-cell">
                                <?= $model->getDeadlineBadge() ?>
                                <?php if ($model->is_holiday): ?>
                                    <div class="mt-1">
                                        <span class="badge bg-warning text-dark rounded-2" style="font-size:0.7rem;">
                                            🎌 <?= Html::encode($model->holiday_name) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 d-none d-lg-table-cell">
                                <?= $model->getProgressPengumpulan() ?>
                            </td>
                            <td class="py-3">
                                <?php if ($model->isSelesai): ?>
                                    <span class="badge-active">Selesai</span>
                                <?php else: ?>
                                    <span class="badge-pending">Pending</span>
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
                                            'data' => ['confirm' => 'Yakin ingin menghapus tugas ini?', 'method' => 'post'],
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