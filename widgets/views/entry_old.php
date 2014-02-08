<div class="p_container">
    <div class="img_avatar">
        <div class="img_holder size55">
            <img src="<?php echo $user->getProfileImage()->getUrl(); ?>" width="55" height="55" />
            <?php echo CHtml::link('', $user->getProfileUrl()); ?>

        </div>
        <?php if (Wall::$currentType == 'dashboard' && $workspace != null): ?>
            <div class="img_second_dashboard">
                <a href="<?php echo Yii::app()->createUrl('workspace/show', array('guid' => $workspace->guid)); ?> "><img class="img_34" src="<?php echo $workspace->getProfileImage()->getUrl(); ?>" width="20" height="20" /></a>
            </div>
        <?php endif; ?>
    </div>
    <div class="p_content_holder">
        <div class="p_headline">
            <span class="username"><?php echo CHtml::link($user->displayName, $user->getProfileUrl()); ?></span>
            <?php echo Yii::t('NotesModule.base', 'created a new note'); ?>
            <span class="time" title="<?php echo $note->created_at; ?>"><?php echo $note->created_at; ?></span>
            <?php
/*
            if (Wall::$currentType == 'dashboard' && $note->space_id != null) {
                $SpaceUrl = Yii::app()->createUrl('workspace/show', array('guid' => $workspace->guid));
                echo '<span class="p_workspace_tag">' . CHtml::link(strtoupper(Helpers::truncateText($workspace->name, 25)), $SpaceUrl) . '</span>';
            }*/

            if ($note->contentMeta->visibility == Content::VISIBILITY_PUBLIC && $note->contentMeta->space_id != null) {
                echo '<span class="p_visibility_tag">'.Yii::t('WallModule.base', 'PUBLIC').'</span>';
            }
            ?>
            <?php if ($note->contentMeta->archived): ?>
                <span class="p_archived_tag"><?php echo Yii::t('WallModule.base', 'ARCHIVED'); ?></span>
            <?php endif; ?>

            <?php if ($note->contentMeta->sticked): ?>
                <span class="p_sticky_tag"><?php echo Yii::t('WallModule.base', 'STICKED'); ?></span>
            <?php endif; ?>
        </div>
        <div class="p_content">
            <div class="note_entry">

                <b> <?php echo $note->title; ?></b>

                <div class="note_snippet">
                    <?php

                        //echo $note->getPadContent();

                        // Show only the first 4 lines

                        foreach (array_slice(explode("\n",$note->getPadContent()),0,4) as $line) {

                            echo Helpers::truncateText($line, 75)."<br />";
                        }


                    ?>
                    <!--
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec id sem turpis. Morbi lacinia, diam eget dignissim imperdiet, quam sem pellentesque est, et porta ligula purus quis diam.
                    -->
                </div>
                <br />
                <div class="button_holder note_edit_button"><a href="#" class="button_white" onclick="openNoteBox('<?php echo Yii::app()->createUrl('notes/note/open', array('id' => $note->id, 'guid' => $workspace->guid)); ?>')"><?php echo Yii::t('NotesModule.base', 'Open full note'); ?></a></div>

                <div class="clearFloats"></div>

                <br />
            </div>

        </div>
        <div class="p_controls">
            <?php $this->widget('application.modules_core.comment.CommentLinkWidget', array('modelName' => 'Note', 'modelId' => $note->id)); ?>
            <?php $this->widget('application.modules_core.like.LikeWidget', array('modelName' => 'Note', 'modelId' => $note->id)); ?>
            <?php $this->widget('application.modules_core.like.ShowLikesWidget', array('modelName' => 'Note', 'modelId' => $note->id)); ?>
            <?php $this->widget('application.modules_core.wall.ArchiveLinkWidget', array('content' => $note)); ?>
            <?php $this->widget('application.modules_core.wall.PermaLinkWidget', array('content' => $note)); ?>
            <?php $this->widget('application.modules_core.wall.StickLinkWidget', array('content' => $note)); ?>

            <span class="wallReadOnlyHide">
                <?php
                if ($note->contentMeta->canDelete()) {
                    echo " - " . HHtml::ajaxLink('Delete', CHtml::normalizeUrl(Yii::app()->createUrl('notes/note/delete', array('id' => $note->id))), array('success' => "function(jsonResp) {
							json = jQuery.parseJSON(jsonResp);
							$.each(json.wallEntryIds, function(i, wallEntryId) {
								currentStream.deleteEntry(wallEntryId); // wall - stream.js function
							});
						}"), array('id' => "noteDeleteLink_" . $note->id)
                    );
                }
                ?>
            </span>
        </div>
    </div>
    <div class="clearFloats"></div>
</div>
<div class="p_comment_container">
    <?php $this->widget('application.modules_core.comment.CommentsWidget', array('modelName' => 'Note', 'modelId' => $note->id)); ?>
</div>
<div class="clearFloats"></div>
<div class="p_border"></div>
</div>
