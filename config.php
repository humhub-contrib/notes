<?php

use humhub\modules\user\models\User;
use humhub\modules\space\widgets\Menu;

return [
    'id' => 'notes',
    'class' => 'humhub\modules\notes\Module',
    'namespace' => 'humhub\modules\notes',
    'events' => [
        ['class' => Menu::className(), 'event' => Menu::EVENT_INIT, 'callback' => ['humhub\modules\notes\Module', 'onSpaceMenuInit']],
        ['class' => User::className(), 'event' => User::EVENT_BEFORE_DELETE, 'callback' => ['humhub\modules\notes\Module', 'onUserDelete']],
    ],
];
