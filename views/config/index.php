<?php

use humhub\models\Setting;
use humhub\modules\notes\libs\EtherpadHelper;
use humhub\modules\ui\form\widgets\ActiveForm;
use humhub\widgets\Button;

?>
<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('NotesModule.base', 'Notes Module Configuration'); ?></div>
    <div class="panel-body">


        <p><?= Yii::t('NotesModule.base', 'The notes module needs a etherpad server up and running!'); ?>
            <br>
            <?= Yii::t('NotesModule.base', 'Please read the module documentation under /protected/modules/notes/docs/install.txt for more details!'); ?>
        </p>

        <br/>
        <?php if (Setting::Get('baseUrl', 'notes') != "" && Setting::Get('apiKey', 'notes') != ""): ?>
            <p><?= Yii::t('NotesModule.base', 'Current Status:'); ?>

                <?php if (EtherpadHelper::testAPIConnection()) : ?>
                    <span style="color:green"><?= Yii::t('NotesModule.base', 'API Connection successful!'); ?></span>
                <?php else: ?>
                    <span style="color:red"><?= Yii::t('NotesModule.base', 'Could not connect to API!'); ?></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <br/>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'baseUrl'); ?>

        <div class="alert alert-info">
            <strong><?= Yii::t('NotesModule.base', 'Etherpad URL Domain'); ?></strong>
            <p>
                <?= Yii::t('NotesModule.base', 'If the Etherpad server is not running under the same domain as the HumHub installation, the Etherpad-Lite plugin "ep_auth_session" must be used.'); ?>
                <a href="https://www.npmjs.com/package/ep_auth_session"><?= Yii::t('NotesModule.base', 'Plugin Homepage'); ?></a>
            </p>
        </div>
        <?= $form->field($model, 'epAuthSessionPlugin')->checkbox(); ?>

        <?= $form->field($model, 'apiKey'); ?>

        <hr>
        <?= Button::save()->submit() ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>