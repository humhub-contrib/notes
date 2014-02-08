
<?php $workspace = $this->getSpace(); ?>

<?php $this->widget('application.modules.notes.widgets.NoteFormWidget', array('workspace' => $workspace)); ?>
<?php $this->widget('application.modules.notes.widgets.NotesStreamWidget', array('type' => Wall::TYPE_SPACE, 'guid' => $workspace->guid)); ?>

