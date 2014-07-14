<?php $this->beginContent('application.modules_core.notification.views.notificationLayoutMail', array('notification' => $notification, 'showSpace' => true)); ?>
<?php echo Yii::t('SpaceModule.notifications', '{userName} created a new note and assigned you.', array(
    '{userName}' => '<strong>'. $creator->displayName .'</strong>',
)); ?>
<?php $this->endContent(); ?>