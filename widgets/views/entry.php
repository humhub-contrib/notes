<?php

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\libs\Helpers;
?>
<?php $this->beginContent('@humhub/modules/content/views/layouts/wallLayout.php', array('object' => $note)); ?>

<div class="notes-sticker">
    <div class="notes-stripe"></div>

    <div class="note_snippet">
        <?php
        foreach (array_slice(explode("\n", $note->getPadContent()), 0, 4) as $line) {
            echo Html::encode(Helpers::truncateText($line, 75));
        }
        ?>
    </div>
</div>


<br/>
<a href="<?php echo $note->content->container->createUrl('/notes/note/open', ['id' => $note->id]); ?>"
   class="btn btn-primary"><?php echo Yii::t('NotesModule.widgets_views_entry', 'Open note'); ?></a>

<?php $this->endContent(); ?>