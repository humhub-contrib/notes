<div class="panel panel-default" id="note_content">

    <div class="panel-heading"><?php echo CHtml::encode($note->title); ?></div>

    <!-- iframe container for etherpad -->
    <iframe id="note" src="<?php echo $padUrl; ?>" height="400" width="100%"></iframe>

    <?php if (count($editors) > 0) { ?>
        <div class="panel-body">
            <div style="font-size: 12px; margin-bottom: 5px;">Editors: </div>
            <?php foreach ($editors as $editor) : ?>
                <div class="note-editor">
                    <a href="<?php echo $editor['url']; ?>">
                        <img src="<?php echo $editor['image']; ?>" class="img-rounded tt img_margin"
                             height="40" width="40" alt="40x40" data-src="holder.js/40x40"
                             style="width: 40px; height: 40px; <?php if ($editor['online'] == "false" && $editor['id'] != Yii::app()->user->id) { ?>opacity: 0.5;<?php } ?>"
                             data-toggle="tooltip" data-placement="top" title=""
                             data-original-title="<strong><?php echo CHtml::encode($editor['displayName']); ?></strong><br><?php echo CHtml::encode($editor['title']); ?> <?php if ($editor['online'] == "true" || $editor['id'] == Yii::app()->user->id) { ?>(Online)<?php } ?>">
                    </a>
                    <div class="note-editor-color" style="background: #<?php echo CHtml::encode($editor['color']); ?>;"></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php } ?>

    <hr>
    <div class="panel-body">
        <a href="<?php echo Yii::app()->createUrl('notes/note/edit', array('id' => $note->id, 'revision' => $revision, 'guid' => Yii::app()->request->getParam('guid'))); ?>"
           class="btn btn-primary"><?php echo Yii::t('NotesModule.views_note_open', 'Save and close'); ?></a>
    </div>

</div>

<script type="text/javascript">

    // adapt iframe size
    setSize();

    window.onresize = function() {

        // adapt iframe size
        setSize();

    }

    function setSize() {

        // bring iframe height to window height
        $('#note').css('height', window.innerHeight - 380 + 'px');

    }


</script>