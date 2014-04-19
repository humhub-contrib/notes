<?php $this->beginContent('application.modules_core.notification.views.notificationLayout', array('notification' => $notification)); ?>

    <strong><?php echo $creator->displayName; ?></strong>
<?php echo Yii::t('NoteModule.base', 'has worked on the note'); ?> "<i><?php echo $targetObject->getContentTitle(); ?></i>"

<?php $this->endContent(); ?>