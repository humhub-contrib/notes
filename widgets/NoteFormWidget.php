<?php

/**
 * This widget is responsible to display the form to add new questions.
 */
class NoteFormWidget extends HWidget {

    public $workspace;

    public function init() {
        $assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
        Yii::app()->clientScript->registerScriptFile($assetPrefix . '/note.js');
    }

    public function run() {
        $this->render('form', array('workspace' => $this->workspace));
    }

}

?>