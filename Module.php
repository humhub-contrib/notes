<?php

namespace humhub\modules\notes;

use Yii;
use yii\helpers\Url;
use humhub\modules\notes\models\Note;
use humhub\modules\notes\models\NoteUserColors;
use humhub\modules\space\models\Space;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;

class Module extends ContentContainerModule
{

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return Url::to(['/notes/config']);
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {

        parent::disable();

        foreach (Note::find()->all() as $note) {
            $note->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        parent::disableContentContainer($container);

        foreach (Note::find()->contentContainer($container)->all() as $note) {
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
