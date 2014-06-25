<?php

class NotesModule extends HWebModule
{

    /**
     * Inits the Module
     */
    public function init()
    {
        $this->setImport(array(
            'notes.models.*',
            'notes.behaviors.*',
            'notes.widgets.*',
            'wall.*',
        ));
    }

    public function behaviors()
    {
        return array(
            'SpaceModuleBehavior' => array(
                'class' => 'application.modules_core.space.SpaceModuleBehavior',
            ),
        );
    }

    public function getConfigUrl()
    {
        return Yii::app()->createUrl('//notes/noteConfig/index');
    }

    /**
     * On User delete, also delete all comments 
     * 
     * @param type $event
     */
    public static function onUserDelete($event)
    {
        foreach (Content::model()->findAllByAttributes(array('created_by' => $event->sender->id, 'object_model' => 'Note')) as $content) {
            $content->delete();
        }

        return true;
    }

    /**
     * On workspace deletion make sure to delete all comments
     * 
     * @param type $event
     */
    public static function onSpaceDelete($event)
    {

        foreach (Content::model()->findAllByAttributes(array('space_id' => $event->sender->id, 'object_model' => 'Note')) as $content) {
            $content->delete();
        }
    }

    /**
     * After the module was disabled globally
     * Do Cleanup
     * 
     * @param type $event
     */
    public static function onDisableModule($event)
    {
        if ($event->params == 'notes') {
            foreach (Content::model()->findAllByAttributes(array('object_model' => 'Note')) as $content) {
                $content->delete();
            }
        }
    }

    /**
     * After the module was uninstalled from a workspace.
     * Do Cleanup
     * 
     * @param type $event
     */
    public static function onSpaceUninstallModule($event)
    {
        if ($event->params == 'notes') {
            foreach (Content::model()->findAllByAttributes(array('space_id' => $event->sender->id, 'object_model' => 'Note')) as $content) {
                $content->delete();
            }
        }
    }

    /**
     * On build of a Space Navigation, check if this module is enabled.
     * When enabled add a menu item
     * 
     * @param type $event
     */
    public static function onSpaceMenuInit($event)
    {

        $space = Yii::app()->getController()->getSpace();

        // Is Module enabled on this workspace?
        if ($space->isModuleEnabled('notes')) {
            $event->sender->addItem(array(
                'label' => Yii::t('NotesModule.base', 'Notes'),
                'url' => Yii::app()->createUrl('/notes/note/show', array('guid' => $space->guid)),
                'icon' => '<i class="fa fa-file-text"></i>',
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'notes'),
            ));
        }
    }

    /**
     * On run of integrity check command, validate all module data
     * 
     * @param type $event
     */
    public static function onIntegrityCheck($event)
    {

        $integrityChecker = $event->sender;
        $integrityChecker->showTestHeadline("Validating Notes Module (" . Note::model()->count() . " entries)");
    }

}
