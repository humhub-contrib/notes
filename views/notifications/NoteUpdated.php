<?php $this->beginContent('application.modules_core.notification.views.notificationLayout', array('notification' => $notification)); ?>
<?php echo Yii::t('SpaceModule.notifications', '{userName} has worked on the note {spaceName}.', array(
    '{userName}' => '<strong>'. $creator->displayName .'</strong>',
    '{spaceName}' => '<strong>'. $targetObject->getContentTitle() .'</strong>'
)); ?>
<?php $this->endContent(); ?>