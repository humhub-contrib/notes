<?php

use humhub\modules\notes\Events;
use humhub\modules\user\models\User;
use humhub\modules\space\widgets\Menu;

return [
    'id' => 'notes',
    'class' => 'humhub\modules\notes\Module',
    'namespace' => 'humhub\modules\notes',
    'events' => [
        ['class' => Menu::class, 'event' => Menu::EVENT_INIT, 'callback' => [Events::class, 'onSpaceMenuInit']],
        ['class' => User::class, 'event' => User::EVENT_BEFORE_DELETE, 'callback' => [Events::class, 'onUserDelete']],
    ],
];
