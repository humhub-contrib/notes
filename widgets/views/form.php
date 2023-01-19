<?php

use humhub\modules\content\widgets\WallCreateContentFormFooter;
use humhub\modules\notes\models\Note;
use humhub\modules\ui\form\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $model Note */
/* @var $form ActiveForm */
/* @var $submitUrl string */
?>
<?= Html::textArea('title', '', [
    'id' => 'contentForm_title',
    'class' => 'form-control autosize contentForm',
    'rows' => '1',
    'placeholder' => Yii::t('NotesModule.base', 'Title of your new note'),
]) ?>

<?= WallCreateContentFormFooter::widget([
    'contentContainer' => $model->content->container,
    'submitUrl' => $submitUrl,
]) ?>