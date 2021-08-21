<?php

use humhub\modules\notes\Assets;
use humhub\widgets\Button;
use yii\helpers\Html;

Assets::register($this);

$saveLinkUrl = $note->content->container->createUrl('/notes/note/edit', ['id' => $note->id, 'revisionCount' => $revisionCount]);
?>
<div class="panel panel-default" id="note_content">

    <div class="panel-heading"><?php echo Html::encode($note->title); ?></div>
    <iframe id="note" src="<?= $padUrl; ?>" height="400" width="100%"></iframe>

    <?php if (count($editors) > 0) { ?>
        <div class="panel-body">
            <div style="font-size: 12px; margin-bottom: 5px;"><?= Yii::t('NotesModule.base', 'Editors:'); ?></div>
            <?php foreach ($editors as $editor) : ?>
                <div class="note-editor">
                    <a href="<?php echo $editor['url']; ?>">
                        <img src="<?= $editor['image']; ?>" class="img-rounded tt img_margin"
                             height="40" width="40" alt="40x40" data-src="holder.js/40x40"
                             style="width: 40px; height: 40px; <?php if ($editor['online'] == "false" && $editor['id'] != Yii::$app->user->id) { ?>opacity: 0.5;<?php } ?>"
                             data-toggle="tooltip" data-placement="top" title=""
                             data-original-title="<?= Html::encode($editor['displayName']); ?>
                             <?php if ($editor['online'] == "true" || $editor['id'] == Yii::$app->user->id) { ?>(Online)<?php } ?>">
                    </a>
                    <div class="note-editor-color"
                         style="background: #<?= Html::encode($editor['color']); ?>;"></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php } ?>

    <hr>
    <div class="panel-body">
        <?= Button::primary(Yii::t('NotesModule.base', 'Save and close'))->link($saveLinkUrl); ?>
    </div>

</div>

<script type="text/javascript">
    // adapt iframe size
    setSize();

    window.onresize = function () {
        // adapt iframe size
        setSize();
    };

    function setSize() {
        // bring iframe height to window height
        $('#note').css('height', window.innerHeight - 380 + 'px');
    }
</script>