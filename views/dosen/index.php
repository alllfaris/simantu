<?php

use app\models\Dosen;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\DosenSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Dosen';
$this->params['breadcrumbs'][] = $this->title;

$models = $dataProvider->getModels();
?>

<div class="dosen-index">

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Manajemen Dosen</h4>
        <p class="text-muted small mb-0">Kelola data dosen pengampu mata kuliah.</p>
    </div>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <div class="card-custom p-3 mb-3 d-flex justify-content-between align-items-center gap-3">
        <div class="d-flex gap-2 flex-wrap">
            <?php $form = \yii\widgets\ActiveForm::begin(['method' => 'get', 'options' => ['class' => 'd-flex gap-2 flex-wrap']]); ?>
                <div class="input-search-wrapper">
                    <i class="bi bi-search input-search-icon"></i>
                    <?= $form->field($searchModel, 'nama_dosen', [
                        'template' => '{input}',
                        'inputOptions' => ['class' => 'form-control input-search', 'placeholder' => 'Cari nama dosen...']
                    ]) ?>
                </div>
            <?php \yii\widgets\ActiveForm::end(); ?>
        </div>
        <?= Html::a('<i class="bi bi-plus-lg"></i> Tambah Dosen', ['create'], [
            'class' => 'btn btn-primary px-4'
        ]) ?>
    </div>

    <div class="card-custom p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-custom table-borderless mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Nama Dosen</th>
                        <th class="d-none d-md-table-cell">NIDN</th>
                        <th class="d-none d-md-table-cell">Email</th>
                        <th class="d-none d-lg-table-cell">Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($models)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                Data dosen tidak ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($models as $i => $model): ?>
                        <tr>
                            <td class="ps-4 py-3 text-muted">
                                <?= $dataProvider->pagination->offset + $i + 1 ?>
                            </td>
                            <td class="py-3 fw-medium"><?= Html::encode($model->nama_dosen) ?></td>
                            <td class="py-3 d-none d-md-table-cell"><?= Html::encode($model->nidn) ?></td>
                            <td class="py-3 d-none d-md-table-cell"><?= Html::encode($model->email) ?></td>
                            <td class="py-3 d-none d-lg-table-cell text-muted small">
                                <?= $model->created_at
                                    ? Yii::$app->formatter->asDatetime($model->created_at, 'dd MMM yyyy, HH:mm')
                                    : '—' ?>
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
                                            'data' => ['confirm' => 'Yakin ingin menghapus dosen ini?', 'method' => 'post'],
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