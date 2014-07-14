<?php $this->beginContent('application.modules_core.activity.views.activityLayoutMail', array('activity' => $activity, 'showSpace' => true)); ?>
<?php echo Yii::t('SpaceModule.activities', '{userName} has worked on the note {noteName}.', array(
    '{userName}' => '<strong>'. $user->displayName .'</strong>',
    '{noteName}' => $target->getContentTitle()
)); ?>
<?php $this->endContent(); ?>
