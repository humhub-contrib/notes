<?php $this->beginContent('application.modules_core.activity.views.activityLayout', array('activity' => $activity)); ?>
<?php echo Yii::t('SpaceModule.activities', '{userName} created a new note {noteName}.', array(
    '{userName}' => '<strong>'. $user->displayName .'</strong>',
    '{noteName}' => '<strong>'. $target->getContentTitle()
)); ?>
<?php $this->endContent(); ?>

