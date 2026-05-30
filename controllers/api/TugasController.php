<?php

namespace app\controllers\api;

use app\models\Tugas;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;

class TugasController extends ActiveController
{
    public $modelClass = 'app\models\Tugas';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = \yii\web\Response::FORMAT_JSON;
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        return $actions;
    }
}