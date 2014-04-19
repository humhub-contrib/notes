<?php $this->beginContent('application.modules_core.notification.views.notificationLayout', array('notification' => $notification)); ?>

<strong><?php echo $creator->displayName; ?></strong>
<?php echo Yii::t('NoteModule.base', 'created a new note and assigned you.'); ?>

<?php $this->endContent(); ?>