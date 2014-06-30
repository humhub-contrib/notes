
<div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('NotesModule.base', 'Notes Module Configuration'); ?></div>
    <div class="panel-body">


        <p><?php echo Yii::t('NotesModule.base', 'The notes module needs a etherpad server up and running!'); ?><br>
            <?php echo Yii::t('NotesModule.base', 'Please read the module documentation under /protected/modules/notes/docs/install.txt for more details!'); ?></p>

        <br/>
        <?php if (HSetting::Get('baseUrl', 'notes') != "" && HSetting::Get('apiKey', 'notes') != ""): ?>
            <p><?php echo Yii::t('NotesModule.base', 'Current Status:'); ?>
                <?php if (Note::testAPIConnection()) : ?>
                    <span style="color:green"><?php echo Yii::t('NotesModule.base', 'API Connection successful!'); ?></span>
                <?php else: ?>
                    <span style="color:red"><?php echo Yii::t('NotesModule.base', 'Could not connect to API!'); ?></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <br/>
        <br/>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'notes-configure-form',
            'enableAjaxValidation' => true,
        ));
        ?>

        <?php echo $form->errorSummary($model); ?>

        <div class="form-group">
            <?php echo $form->labelEx($model, 'baseUrl'); ?>
            <?php echo $form->textField($model, 'baseUrl', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'baseUrl'); ?>
        </div>


        <div class="form-group">
            <?php echo $form->labelEx($model, 'apiKey'); ?>
            <?php echo $form->textField($model, 'apiKey', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'apiKey'); ?>
        </div>



        <hr>
        <?php echo CHtml::submitButton(Yii::t('NotesModule.base', 'Save & Test'), array('class' => 'btn btn-primary')); ?>
        <a class="btn btn-default" href="<?php echo $this->createUrl('//admin/module'); ?>"><?php echo Yii::t('AdminModule.base', 'Back to modules'); ?></a>

        <?php $this->endWidget(); ?>
    </div>
</div>