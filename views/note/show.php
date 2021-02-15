<?php

use humhub\modules\content\widgets\Stream;
use humhub\modules\notes\permissions\CreateNote;
use humhub\modules\notes\widgets\WallCreateForm;


?>

<?= WallCreateForm::widget(['contentContainer' => $contentContainer]) ?>

<?php
echo Stream::widget([
    'contentContainer' => $contentContainer,
    'streamAction' => '/notes/note/stream',
    'messageStreamEmpty' => Yii::t('NotesModule.base', 'There are no notes yet!'),
    'messageStreamEmptyCss' => (!$contentContainer->permissionManager->can(new CreateNote())) ? 'placeholder-empty-stream' : '',
    'filters' => []
]);
?>



