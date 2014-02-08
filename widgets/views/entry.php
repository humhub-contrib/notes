<?php
/**
 * This view represents a wall entry of a note.
 * Used by NoteWallEntryWidget to show Notes inside a wall.
 *
 * @property User $user the user which created this post
 * @property Note $note the current note
 * @property Space $space the current space
 *
 * @package humhub.modules.notes
 * @since 0.5
 */
?>
<div class="panel panel-default">
    <div class="panel-body">
        <?php $this->beginContent('application.modules_core.wall.views.wallLayout', array('object' => $note)); ?>

        <b><?php echo $note->title; ?></b>

        <div class="note_snippet">
            <?php
            foreach (array_slice(explode("\n", $note->getPadContent()), 0, 4) as $line) {
                echo Helpers::truncateText($line, 75);
            }
            ?>
        </div>
        <br/>
        <a href="<?php echo Yii::app()->createUrl('notes/note/open', array('id' => $note->id, 'guid' => $space->guid)); ?>"
           class="btn btn-primary"><?php echo Yii::t('NotesModule.base', 'Open full note'); ?></a>

        <?php $this->endContent(); ?>
    </div>
</div>

