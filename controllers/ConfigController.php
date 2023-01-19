<?php

namespace humhub\modules\notes\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\notes\Module;
use Yii;
use humhub\modules\notes\models\ConfigureForm;

/**
 * Class ConfigController
 *
 * @property Module $module
 * @package humhub\modules\notes\controllers
 */
class ConfigController extends Controller
{

    public function actionIndex()
    {
        $form = new ConfigureForm();
        $form->baseUrl = rtrim($this->module->settings->get('baseUrl', ''), '/') . '/';;
        $form->apiKey = $this->module->settings->get('apiKey');
        $form->epAuthSessionPlugin = $this->module->settings->get('epAuthSessionPlugin');

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $this->module->settings->set('baseUrl', $form->baseUrl);
            $this->module->settings->set('apiKey', $form->apiKey);
            $this->module->settings->set('epAuthSessionPlugin', $form->epAuthSessionPlugin);

            return $this->redirect(['/notes/config']);
        }

        return $this->render('index', array('model' => $form));
    }

}

?>
