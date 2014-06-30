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
        array('class' => 'SpaceMenuWidget', 'event' => 'onInit', 'callback' => array('NotesModule', 'onSpaceMenuInit')),
        array('class' => 'User', 'event' => 'onBeforeDelete', 'callback' => array('NotesModule', 'onUserDelete')),        
    )
));
?>