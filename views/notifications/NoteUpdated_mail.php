<?php $this->beginContent('application.modules_core.notification.views.notificationLayoutMail', array('notification' => $notification, 'showSpace' => true)); ?>
<?php echo Yii::t('NotesModule.views_notifications_NoteUpdated', '{userName} has worked on the note {spaceName}.', array(
    '{userName}' => '<strong>'. $creator->displayName .'</strong>',
    '{spaceName}' => '<strong>'. $targetObject->getContentTitle() .'</strong>'
)); ?>
<?php $this->endContent(); ?>