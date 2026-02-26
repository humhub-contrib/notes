<?php

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\notes\permissions\CreateNote;
use humhub\modules\notes\widgets\WallCreateForm;
use humhub\modules\stream\widgets\StreamViewer;

/* @var ContentContainerActiveRecord $contentContainer */
?>

<?= WallCreateForm::widget(['contentContainer' => $contentContainer]) ?>

<?= StreamViewer::widget([
    'contentContainer' => $contentContainer,
    'streamAction' => '/notes/note/stream',
    'messageStreamEmpty' => Yii::t('NotesModule.base', 'There are no notes yet!'),
    'messageStreamEmptyCss' => (!$contentContainer->permissionManager->can(new CreateNote())) ? 'placeholder-empty-stream' : '',
    'filters' => [],
]) ?>



