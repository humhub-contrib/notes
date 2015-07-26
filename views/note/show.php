<?php

echo \humhub\modules\notes\widgets\WallCreateForm::widget([
    'contentContainer' => $contentContainer,
]);
?>

<?php
echo \humhub\modules\content\widgets\Stream::widget(array(
    'contentContainer' => $contentContainer,
    'streamAction' => '/notes/note/stream',
    'messageStreamEmpty' => Yii::t('NotesModule.widgets_views_stream', 'There are no notes yet!'),
    'messageStreamEmptyCss' => ($contentContainer->canWrite()) ? 'placeholder-empty-stream' : '',
    'filters' => []
));
?>



