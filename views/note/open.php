<div class="panel panel-default" id="note_content">

    <div class="panel-heading"><?php echo $note->title; ?></div>

    <!-- iframe container for etherpad -->
    <iframe id="note" src="<?php echo $padUrl; ?>" height="400" width="100%"></iframe>
    <hr>
    <div class="panel-body">
        <a href="<?php echo Yii::app()->createAbsoluteUrl('space/space', array('guid' => Yii::app()->request->getParam('guid'))) ?>"
           class="btn btn-primary"><?php echo Yii::t('NotesModule.base', 'Save and close'); ?></a>
    </div>

</div>

<script type="text/javascript">

    // adapt iframe size
    setSize();

    window.onresize = function () {

        // adapt iframe size
        setSize();

    }

    function setSize() {

        // bring iframe height to window height
        $('#note').css('height', window.innerHeight - 280 + 'px');

    }

</script>