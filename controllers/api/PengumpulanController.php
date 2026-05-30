<?php

namespace app\controllers\api;

use yii\rest\ActiveController;

class PengumpulanController extends ActiveController
{
    public $modelClass = 'app\models\PengumpulanTugas';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = \yii\web\Response::FORMAT_JSON;
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update'], $actions['delete']);
        return $actions;
    }
}