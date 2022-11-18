<?php

namespace humhub\modules\notes;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\notes\models\Note;
use humhub\modules\notes\models\NoteUserColors;
use humhub\modules\space\models\Space;
use Yii;
use yii\helpers\Url;

class Module extends ContentContainerModule
{
    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    public static function onSpaceMenuInit($event)
    {
        /** @var Space $space */
        $space = $event->sender->space;
        if ($space->moduleManager->isEnabled('notes')) {
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

        foreach (Note::find()->all() as $note) {
            $note->delete();
        }

        parent::disable();
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

}
