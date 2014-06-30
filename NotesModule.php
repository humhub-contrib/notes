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
     * On global module disable
     */
    public function disable()
    {
        if (parent::disable()) {
            foreach (Content::model()->findAllByAttributes(array('object_model' => 'Note')) as $content) {
                $content->delete();
            }
            return true;
        }

        return false;
    }

    /**
     * On disabling this module on a space, deleted all module -> space 
     * related content.
     * 
     * Method sub is provided by "SpaceModuleBehavior"
     * 
     * @param Space $space
     */
    public function disableSpaceModule(Space $space)
    {
        foreach (Content::model()->findAllByAttributes(array('space_id' => $space->id, 'object_model' => 'Note')) as $content) {
            $content->delete();
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
                'group' => 'modules',
                'url' => Yii::app()->createUrl('/notes/note/show', array('guid' => $space->guid)),
                'icon' => '<i class="fa fa-file-text"></i>',
                'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'notes'),
            ));
        }
    }

}
