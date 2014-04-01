<?php

/**
 * This widget is used include the note form.
 * It normally should be placed above a steam.
 *
 * @package humhub.modules.notes.widgets
 * @since 0.5
 */
class NoteFormWidget extends ContentFormWidget {

    public $submitUrl = 'notes/note/create';

    public function renderForm() {
        $this->form = $this->render('form', array(), true);
    }

}

?>