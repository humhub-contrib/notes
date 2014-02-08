<div class="panel panel-default">
    <div class="panel-body">
        <?php echo CHtml::form('#', 'post', array('id' => 'note_addform')); ?>

        <?php echo CHtml::textArea("title", '', array('id' => "noteFrom_messageField", 'class' => 'form-control autosize', 'rows' => '1', 'placeholder' => Yii::t('NotesModule.base', "Title of your new note..."))); ?>


        <div id="noteForm_more">
            <hr>
            <?php

            $url = Yii::app()->createUrl('notes/note/open', array('id' => 'NOTEID', 'guid' => $workspace->guid));

            echo CHtml::ajaxButton('Note!', array('/notes/note/create', 'guid' => $workspace->guid, 'ajax' => 1), array(
                'type' => 'POST',
                'success' => 'function(response){
			json = jQuery.parseJSON(response);
			currentStream.prependEntry(json.wallEntryId);
			url = "' . $url . '";
			url = url.replace("NOTEID", json.id);

			// Clear Form
            $("#noteFrom_messageField").val("");
            $("#noteFrom_messageField").css("height", "30px");
            $("#public").attr("checked", false);
            $("#noteForm_more").hide();

		}',
            ), array('class' => 'btn btn-info'));

            ?>
            <div class="pull-right">
                <?php if ($workspace->canShare()): ?>
                    <div class="checkbox">
                        <label>
                            <?php echo CHtml::checkbox("public", "", array()); ?> <?php echo Yii::t('NotesModule.base', 'This is a public note (also non-members)'); ?>
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <?php echo CHtml::endForm(); ?>
</div>


<div class="clearFloats"></div>

<script type="text/javascript">

    jQuery('#noteForm_more').hide();


    // Remove info text from the textinput
    jQuery('#noteFrom_messageField').click(function () {

        jQuery('#noteForm_more').show();

        // Change textinput content just at the first click
        if (jQuery(this).attr('alt') != "ready") {

            // Change textfield color
            jQuery(this).css('color', '#3e3e3e');

            // Save, that the first click is done in the attribute
            jQuery(this).attr('alt', 'ready');

            // remove the placeholder text
            jQuery(this).val('');

        }
    });

    // add autosize function to input
    $('.autosize').autosize();

</script>