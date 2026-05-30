<?php

namespace app\controllers;

use app\models\Mahasiswa;
use app\models\MahasiswaSearch;
use app\models\PengumpulanTugas;
use app\models\Tugas;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


/**
 * MahasiswaController implements the CRUD actions for Mahasiswa model.
 */
class MahasiswaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['dashboard', 'daftar-tugas', 'tugas-detail', 'kumpul-tugas'],
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return \Yii::$app->user->identity->role === 'mahasiswa';
                            },
                        ],
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['@'],
                            'matchCallback' => function ($rule, $action) {
                                return \Yii::$app->user->identity->role === 'admin';
                            },
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }


    public function actionDashboard()
    {
        $user = Yii::$app->user->identity;

        $mahasiswa = Mahasiswa::findOne([
            'user_id' => $user->id
        ]);

        $totalTugas = Tugas::find()->count();

        $sudahKumpul = 0;

        if ($mahasiswa) {
            $sudahKumpul = PengumpulanTugas::find()
                ->where([
                    'mahasiswa_id' => $mahasiswa->id
                ])
                ->count();
        }

        $belumKumpul = max(0, $totalTugas - $sudahKumpul);

        

        return $this->render('dashboard', [
            'mahasiswa'    => $mahasiswa,
            'totalTugas'   => $totalTugas,
            'sudahKumpul'  => $sudahKumpul,
            'belumKumpul'  => $belumKumpul,
        ]);
    }

    public function actionDaftarTugas()
    {
        $user = Yii::$app->user->identity;

        $mahasiswa = Mahasiswa::findOne([
            'user_id' => $user->id
        ]);

        $tugasList = Tugas::find()
            ->with('matkul')
            ->orderBy(['deadline' => SORT_ASC])
            ->all();

        return $this->render('daftar-tugas', [
            'mahasiswa' => $mahasiswa,
            'tugasList' => $tugasList,
        ]);
    }

    public function actionTugasDetail($id)
    {
        $tugas = Tugas::findOne($id);
        if (!$tugas) {
            throw new \yii\web\NotFoundHttpException('Tugas tidak ditemukan.');
        }

        $user = Yii::$app->user->identity;
        $mahasiswa = Mahasiswa::findOne(['user_id' => $user->id]);

        $pengumpulan = $mahasiswa ? PengumpulanTugas::findOne([
            'tugas_id' => $id,
            'mahasiswa_id' => $mahasiswa->id,
        ]) : null;

        return $this->render('tugas-detail', [
            'tugas'       => $tugas,
            'mahasiswa'   => $mahasiswa,
            'pengumpulan' => $pengumpulan,
        ]);
    }

    public function actionKumpulTugas($id)
    {
        $user = Yii::$app->user->identity;

        $mahasiswa = Mahasiswa::findOne([
            'user_id' => $user->id
        ]);

        if (!$mahasiswa) {
            Yii::$app->session->setFlash(
                'error',
                'Data mahasiswa tidak ditemukan.'
            );

            return $this->redirect([
                '/mahasiswa/dashboard'
            ]);
        }

        // Cek apakah tugas ada
        $tugas = Tugas::findOne($id);

        if (!$tugas) {
            Yii::$app->session->setFlash(
                'error',
                'Tugas tidak ditemukan.'
            );

            return $this->redirect([
                '/mahasiswa/dashboard'
            ]);
        }

        // Cegah upload ganda
        $cek = PengumpulanTugas::findOne([
            'tugas_id' => $id,
            'mahasiswa_id' => $mahasiswa->id,
        ]);

        if ($cek) {
            Yii::$app->session->setFlash(
                'error',
                'Tugas sudah pernah dikumpulkan.'
            );

            return $this->redirect([
                '/mahasiswa/tugas-detail',
                'id' => $id
            ]);
        }

        $model = new PengumpulanTugas();

        $model->tugas_id = $id;
        $model->mahasiswa_id = $mahasiswa->id;

        if ($this->request->isPost) {

            if ($model->load($this->request->post())) {

                $model->uploadFile = \yii\web\UploadedFile::getInstance(
                    $model,
                    'uploadFile'
                );

                if ($model->uploadFile) {

                    $fileName =
                        time() . '_' .
                        $model->uploadFile->baseName . '.' .
                        $model->uploadFile->extension;

                    $path = Yii::getAlias('@webroot')
                        . '/uploads/tugas/'
                        . $fileName;

                    $model->uploadFile->saveAs($path);

                    $model->file_tugas =
                        'uploads/tugas/' . $fileName;
                }
                
                $model->link_tugas = $model->link_tugas ?: null;

                $model->waktu_kumpul = date('Y-m-d H:i:s');

                
                if (
                    $tugas->deadline &&
                    strtotime($model->waktu_kumpul)
                    <= strtotime($tugas->deadline)
                ) {

                    $model->status_kumpul = 'Tepat Waktu';

                } else {

                    $model->status_kumpul = 'Terlambat';
                }

                if ($model->save()) {

                    Yii::$app->session->setFlash(
                        'success',
                        'Tugas berhasil dikumpulkan!'
                    );

                    return $this->redirect([
                        '/mahasiswa/tugas-detail',
                        'id' => $id
                    ]);
                }

                
                Yii::$app->session->setFlash(
                    'error',
                    json_encode($model->errors)
                );
            } 
        }

        return $this->redirect([
            '/mahasiswa/tugas-detail',
            'id' => $id
        ]);
    }

    /**
     * Lists all Mahasiswa models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MahasiswaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Mahasiswa model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Mahasiswa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Mahasiswa();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mahasiswa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mahasiswa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Mahasiswa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Mahasiswa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mahasiswa::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
