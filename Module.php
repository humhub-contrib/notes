<?php

namespace humhub\modules\notes;

use Yii;
use yii\helpers\Url;
use humhub\modules\notes\models\Note;
use humhub\modules\notes\models\NoteUserColors;

class Module extends \humhub\components\Module
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \humhub\modules\space\behaviors\SpaceModule::className(),
        ];
    }

    public function getConfigUrl()
    {
        return Url::to(['/notes/config']);
    }

    public function disable()
    {

        if (parent::disable()) {

            foreach (Note::find()->all() as $note) {
                $note->delete();
            }

            return true;
        }

        return false;
    }

    public function disableSpaceModule(Space $space)
    {
        foreach (Note::find()->contentContainer($space)->all() as $note) {
            $note->delete();
        }
    }

    public static function onSpaceMenuInit($event)
    {
        $space = $event->sender->space;
        if ($space->isModuleEnabled('notes')) {
            $event->sender->addItem(array(
                'label' => Yii::t('NotesModule.base', 'Notes'),
                'group' => 'modules',
                'url' => $space->createUrl('/notes/note/show'),
                'icon' => '<i class="fa fa-file-text"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'notes'),
            ));
        }
    }

    public static function onUserDelete($event)
    {
        NoteUserColors::deleteAll(array('user_id' => $event->sender->id));
        return true;
    }

}
