<div id="noteStream">

	<!-- DIV for an normal wall stream -->
	<div class="s2_stream" style="display:none">
		
		<div class="s2_streamContent"></div>
		<div class="loader streamLoader"></div>

		<div class="emptyStreamMessage">
				<div class="placeholder_text">
                    <i class="icon-frown"></i> <?php echo Yii::t('NotesModule.base', 'There are no notes yet!'); ?>
				</div>
		</div>

		<div class="emptyFilterStreamMessage">
				<div class="placeholder_text">
                    <i class="icon-frown"></i> <?php echo Yii::t('NotesModule.base', 'No notes found which matches your current filter(s)!'); ?>
				</div>
		</div>
		
	</div>
	
	<!-- DIV for an single wall entry -->
	<div class="s2_single">
                <div class="back_button_holder">
                    <a href="#" class="singleBackLink button_white"><?php echo Yii::t('WallModule.base', 'Back to stream'); ?></a>
                </div>
                <div class="p_border"></div>

		<div class="s2_singleContent"></div>
		<div class="loader streamLoaderSingle"></div>
	</div>
</div>


<script>
	// Kill current stream
	if (currentStream) {
		currentStream.clear();
	}

	s = new Stream("#noteStream", "<?php echo $startUrl;?>", "<?php echo $reloadUrl;?>", "<?php echo $singleEntryUrl;?>");
	s.showStream();
	currentStream = s;
	
</script>


