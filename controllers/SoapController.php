<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\services\TugasSoapService;

class SoapController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionTugas()
    {
        header('Content-Type: text/xml; charset=utf-8');

        $uri = Yii::$app->request->absoluteUrl;

        $server = new \SoapServer(null, [
            'uri' => $uri,
        ]);

        $server->setObject(new TugasSoapService());
        $server->handle();

        // Beritahu Yii response sudah dikirim
        Yii::$app->response->isSent = true;
    }
}