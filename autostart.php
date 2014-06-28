<?php

Yii::app()->moduleManager->register(array(
    'id' => 'notes',
    'class' => 'application.modules.notes.NotesModule',
    'import' => array(
        'application.modules.notes.*',
        'application.modules.notes.models.*',
        'application.modules.notes.libs.*',
        'application.modules.notes.notifications.*',
    ),
    // Events to Catch 
    'events' => array(
        array('class' => 'User', 'event' => 'onBeforeDelete', 'callback' => array('NotesModule', 'onUserDelete')),
        array('class' => 'Space', 'event' => 'onBeforeDelete', 'callback' => array('NotesModule', 'onSpaceDelete')),
        array('class' => 'SpaceMenuWidget', 'event' => 'onInit', 'callback' => array('NotesModule', 'onSpaceMenuInit')),
        array('class' => 'ModuleManager', 'event' => 'onDisable', 'callback' => array('NotesModule', 'onDisableModule')),
        array('class' => 'Space', 'event' => 'onUninstallModule', 'callback' => array('NotesModule', 'onSpaceUninstallModule')),
        array('class' => 'IntegrityChecker', 'event' => 'onRun', 'callback' => array('NotesModule', 'onIntegrityCheck')),
    )
));
?>