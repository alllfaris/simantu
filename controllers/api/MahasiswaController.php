<?php

namespace app\controllers\api;

use yii\rest\ActiveController;

class MahasiswaController extends ActiveController
{
    public $modelClass = 'app\models\Mahasiswa';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = \yii\web\Response::FORMAT_JSON;
        return $behaviors;
    }

    // Hanya izinkan GET (index & view), tidak bisa POST/PUT/DELETE
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update'], $actions['delete']);
        return $actions;
    }
}