<?php $this->beginContent('application.modules_core.activity.views.activityLayout', array('activity' => $activity)); ?>
<?php echo Yii::t('NotesModule.views_activities_NoteUpdated', '{userName} has worked on the note {noteName}.', array(
    '{userName}' => '<strong>'. CHtml::encode($user->displayName) .'</strong>',
    '{noteName}' => ActivityModule::formatOutput($target->getContentTitle())
)); ?>
<?php $this->endContent(); ?>

