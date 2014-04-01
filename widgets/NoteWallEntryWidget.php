<?php

/**
 * This widget is used to show a note inside a wall.
 *
 * @package humhub.modules.notes
 * @since 0.5
 */
class NoteWallEntryWidget extends HWidget {

    public $note;

    /**
     * Inits, publishs required javascript resources
     */
    public function init() {
        //$assetPrefix = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../resources', true, 0, defined('YII_DEBUG'));
        //Yii::app()->clientScript->registerScriptFile($assetPrefix . '/note.js');
    }

    public function run() {
        $user = $this->note->creator;
        $this->render('entry', array(
            'note' => $this->note,
            'user' => $user,
            'space' => $this->note->content->container)
        );
    }

}

?>