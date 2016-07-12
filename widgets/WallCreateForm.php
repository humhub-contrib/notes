<?php

namespace humhub\modules\notes\widgets;

/**
 * This widget is used include the note form.
 * It normally should be placed above a steam.
 *
 * @package humhub.modules.notes.widgets
 * @since 0.5
 */
class WallCreateForm extends \humhub\modules\content\widgets\WallCreateContentForm
{

    public $submitUrl = '/notes/note/create';

    public function renderForm()
    {

        if (!$this->contentContainer->permissionManager->can(new \humhub\modules\notes\permissions\CreateNote())) {
            return;
        }

        return $this->render('form', array());
    }

}

?>