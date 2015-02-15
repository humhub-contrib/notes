<?php $this->beginContent('application.modules_core.notification.views.notificationLayout', array('notification' => $notification)); ?>
<?php

echo Yii::t('NotesModule.views_notifications_NoteUpdated', '{userName} has worked on the note {spaceName}.', array(
    '{userName}' => '<strong>' . CHtml::encode($creator->displayName) . '</strong>',
    '{spaceName}' => '<strong>' . NotificationModule::formatOutput($targetObject->getContentTitle()) . '</strong>'
));
?>
<?php $this->endContent(); ?>