<?php $this->beginContent('application.modules_core.activity.views.activityLayout', array('activity' => $activity)); ?>
<?php echo Yii::t('SpaceModule.activities', '{userName} has worked on the note {noteName}.', array(
    '{userName}' => '<strong>'. $user->displayName .'</strong>',
    '{noteName}' => $target->getContentTitle()
)); ?>
<?php $this->endContent(); ?>

