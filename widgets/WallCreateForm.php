<?php

namespace humhub\modules\notes\widgets;

use humhub\modules\content\widgets\WallCreateContentForm;
use humhub\modules\notes\models\Note;
use humhub\modules\notes\permissions\CreateNote;
use humhub\modules\ui\form\widgets\ActiveForm;

/**
 * This widget is used include the note form.
 * It normally should be placed above a steam.
 *
 * @package humhub.modules.notes.widgets
 * @since 0.5
 */
class WallCreateForm extends WallCreateContentForm
{

    public $submitUrl = '/notes/note/create';

    /**
     * @inheritdoc
     */
    public function renderActiveForm(ActiveForm $form): string
    {
        return $this->render('form', [
            'model' => new Note($this->contentContainer),
            'form' => $form,
            'submitUrl' => $this->submitUrl,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->contentContainer->permissionManager->can(CreateNote::class)) {
            return '';
        }

        return parent::run();
    }

}