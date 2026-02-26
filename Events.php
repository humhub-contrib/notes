<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\notes;

use humhub\helpers\ControllerHelper;
use humhub\modules\notes\models\NoteUserColors;
use humhub\modules\space\widgets\Menu as SpaceMenu;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\user\models\User;
use Yii;

class Events
{
    public static function onSpaceMenuInit($event)
    {
        /* @var SpaceMenu $menu */
        $menu = $event->sender;

        if ($menu->space->moduleManager->isEnabled('notes')) {
            $menu->addEntry(new MenuLink([
                'label' => Yii::t('NotesModule.base', 'Notes'),
                'url' => $menu->space->createUrl('/notes/note/show'),
                'icon' => 'file-text',
                'isActive' => ControllerHelper::isActivePath('notes'),
            ]));
        }
    }

    public static function onUserDelete($event)
    {
        /* @var User $user */
        $user = $event->sender;

        NoteUserColors::deleteAll(['user_id' => $user->id]);
    }
}