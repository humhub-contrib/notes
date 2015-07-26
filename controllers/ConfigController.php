<?php

namespace humhub\modules\notes\controllers;

use Yii;
use humhub\modules\notes\models\ConfigureForm;
use humhub\models\Setting;

class ConfigController extends \humhub\modules\admin\components\Controller
{

    public function actionIndex()
    {
        $form = new ConfigureForm();
        $form->baseUrl = Setting::Get('baseUrl', 'notes');
        $form->apiKey = Setting::Get('apiKey', 'notes');

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $form->baseUrl = Setting::Set('baseUrl', $form->baseUrl, 'notes');
            $form->apiKey = Setting::Set('apiKey', $form->apiKey, 'notes');
            return $this->redirect(['/notes/config']);
        }

        return $this->render('index', array('model' => $form));
    }

}

?>
