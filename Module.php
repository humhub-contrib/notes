<?php

namespace humhub\modules\notes;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\notes\models\Note;
use humhub\modules\space\models\Space;
use yii\helpers\Url;

class Module extends ContentContainerModule
{
    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getContentClasses(): array
    {
        return [Note::class];
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
            $note->hardDelete();
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
            $note->hardDelete();
        }
    }

}
