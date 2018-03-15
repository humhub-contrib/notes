<?php

use humhub\models\Setting;
use humhub\modules\notes\libs\EtherpadHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\notes\models\Note;
use humhub\compat\CActiveForm;
?>
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('NotesModule.views_noteConfig_index', 'Notes Module Configuration'); ?></div>
    <div class="panel-body">


        <p><?php echo Yii::t('NotesModule.views_noteConfig_index', 'The notes module needs a etherpad server up and running!'); ?><br>
            <?php echo Yii::t('NotesModule.views_noteConfig_index', 'Please read the module documentation under /protected/modules/notes/docs/install.txt for more details!'); ?></p>

        <br/>
        <?php if (Setting::Get('baseUrl', 'notes') != "" && Setting::Get('apiKey', 'notes') != ""): ?>
            <p><?php echo Yii::t('NotesModule.views_noteConfig_index', 'Current Status:'); ?>
                
                <?php if (EtherpadHelper::testAPIConnection()) : ?>
                    <span style="color:green"><?php echo Yii::t('NotesModule.views_noteConfig_index', 'API Connection successful!'); ?></span>
                <?php else: ?>
                    <span style="color:red"><?php echo Yii::t('NotesModule.views_noteConfig_index', 'Could not connect to API!'); ?></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <br/>
        <br/>

        <?php $form = CActiveForm::begin(); ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'baseUrl'); ?>
            <?php echo $form->textField($model, 'baseUrl', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'baseUrl'); ?>
            <p class="help-block"><?php echo Yii::t('NotesModule.views_noteConfig_index', 'e.g. http://yourdomain/pad/'); ?></p>
        </div>


        <div class="form-group">
            <?php echo $form->labelEx($model, 'apiKey'); ?>
            <?php echo $form->textField($model, 'apiKey', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'apiKey'); ?>
        </div>



        <hr>
        <?php echo Html::submitButton(Yii::t('NotesModule.views_noteConfig_index', 'Save & Test'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo Url::to(['/admin/module']); ?>"><?php echo Yii::t('NotesModule.views_noteConfig_index', 'Back to modules'); ?></a>

        <?php CActiveForm::end(); ?>
    </div>
</div>