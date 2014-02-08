<?php

class NoteConfigController extends Controller {

    public $subLayout = "application.modules_core.admin.views._layout";

    /**
     * @return array action filters
     */
    public function filters() {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules() {
        return array(
            array('allow',
                'expression' => 'Yii::app()->user->isAdmin()',
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }


    /**
     * Configuration Action for Super Admins
     */
    public function actionIndex() {

        Yii::import('notes.forms.*');

        $form = new NotesConfigureForm;

        // uncomment the following code to enable ajax-based validation
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'notes-configure-form') {
            echo CActiveForm::validate($form);
            Yii::app()->end();
        }

        if (isset($_POST['NotesConfigureForm'])) {
            $_POST['NotesConfigureForm'] = Yii::app()->input->stripClean($_POST['NotesConfigureForm']);
            $form->attributes = $_POST['NotesConfigureForm'];

            if ($form->validate()) {

                $form->baseUrl = HSetting::Set('baseUrl', $form->baseUrl, 'notes');
                $form->apiKey = HSetting::Set('apiKey', $form->apiKey, 'notes');

#                $this->redirect(Yii::app()->createUrl('admin/manageModules'));
                $this->redirect(Yii::app()->createUrl('notes/noteconfig/index'));
            }
        } else {
            $form->baseUrl = HSetting::Get('baseUrl', 'notes');
            $form->apiKey = HSetting::Get('apiKey', 'notes');
        }

        $this->render('index', array('model' => $form));
    }
}

?>
