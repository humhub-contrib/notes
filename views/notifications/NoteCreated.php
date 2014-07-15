<?php $this->beginContent('application.modules_core.notification.views.notificationLayout', array('notification' => $notification)); ?>
<?php echo Yii::t('NotesModule.views_notifications_NoteCreated', '{userName} created a new note and assigned you.', array(
    '{userName}' => '<strong>'. $creator->displayName .'</strong>',
)); ?>
<?php $this->endContent(); ?>