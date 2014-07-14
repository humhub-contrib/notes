<?php $this->beginContent('application.modules_core.activity.views.activityLayoutMail', array('activity' => $activity, 'showSpace' => true)); ?>
<?php echo Yii::t('SpaceModule.activities', '{userName} created a new note {noteName}.', array(
    '{userName}' => '<strong>'. $user->displayName .'</strong>',
    '{noteName}' => '<strong>'. $target->getContentTitle() .'</strong>'
)); ?>
<?php $this->endContent(); ?>
