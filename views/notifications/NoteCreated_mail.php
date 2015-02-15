<?php $this->beginContent('application.modules_core.notification.views.notificationLayoutMail', array('notification' => $notification, 'showSpace' => true)); ?>
<?php echo Yii::t('NotesModule.views_notifications_NoteCreated', '{userName} created a new note and assigned you.', array(
    '{userName}' => '<strong>'. CHtml::encode($creator->displayName) .'</strong>',
)); ?>
<?php $this->endContent(); ?>