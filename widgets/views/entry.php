<?php

use humhub\modules\notes\Assets;
use humhub\widgets\Button;
use yii\helpers\Html;

Assets::register($this);

$openUrl = $note->content->container->createUrl('/notes/note/open', ['id' => $note->id]);
?>
<div class="notes-sticker">
    <div class="notes-title"><?= Html::encode($note->title); ?></div>

    <?php foreach (array_slice(explode("\n", $note->getPadContent()), 0, 4) as $line): ?>
        <?php if (empty(trim($line))) { continue; } ?>
        <div class="notes-line"><?= Html::encode($line); ?></div>
    <?php endforeach; ?>

</div>

<br/>
<?= Button::primary(Yii::t('NotesModule.base', 'Open note'))->link($openUrl) ?>
