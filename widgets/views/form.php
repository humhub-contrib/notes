<?php

use yii\helpers\Html;

?>
<?php echo Html::textArea("title", '', array('id' => "contentForm_title", 'class' => 'form-control autosize contentForm', 'rows' => '1', 'placeholder' => Yii::t('NotesModule.widgets_views_form', "Title of your new note"))); ?>
