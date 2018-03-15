<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\notes\libs;

use Colors\RandomColor;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\ContentContainerSetting;
use humhub\modules\notes\models\NoteUserColors;
use humhub\modules\notes\Module;
use humhub\modules\user\models\User;
use Yii;


/**
 * Class EtherpadHelper
 * @package humhub\modules\notes\libs
 */
class EtherpadHelper
{

    private static $_etherClient;

    /**
     * Returns the HumHub user by given Etherpad Author Id
     *
     * @param $authorId
     * @return User|null
     */
    public static function getUserByEtherpadAuthorId($authorId)
    {
        $setting = ContentContainerSetting::findOne(['module_id' => 'notes', 'name' => 'etherpadAuthorId', 'value' => $authorId]);
        if ($setting !== null) {
            $user = $setting->contentcontainer->getPolymorphicRelation();
            /* @var User $user */
            return $user;
        }

        // If not found try lookup by Display Name (Old StylE)
        $user = User::findOne(['username' => static::getPadClient()->getAuthorName($authorId)]);
        if ($user !== null) {
            return $user;
        }

        return $user;
    }

    /**
     * Returns an instance of the Etherpad Client.
     *
     * Maybe singleton in future.
     *
     * @return EtherpadLiteClient
     */
    public static function getPadClient()
    {
        $module = Yii::$app->getModule('notes');

        if (!self::$_etherClient) {
            self::$_etherClient = new EtherpadLiteClient($module->settings->get('apiKey'), $module->settings->get('baseUrl') . "api");
        }

        return self::$_etherClient;
    }

    /**
     * Get Etherpad Group Id for given container
     *
     * @param ContentContainerActiveRecord $container
     * @return string the group id in Etherpad
     */
    public static function getPadGroupId(ContentContainerActiveRecord $container)
    {
        try {
            return self::getPadClient()->createGroupIfNotExistsFor($container->guid)->groupID;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Returns the Etherpad Author ID of the current user
     *
     * @return string the author id in Etherpad
     */
    public static function getPadAuthorId()
    {
        try {
            /* @var Module $notes */
            $notes = Yii::$app->getModule('notes');

            $author = self::getPadClient()->createAuthorIfNotExistsFor(Yii::$app->user->guid, Yii::$app->user->getIdentity()->displayName);
            $notes->settings->user()->set('etherpadAuthorId', $author->authorID);

            return $author->authorID;
        } catch (\Exception $e) {
            echo "\n\ncreateAuthorIfNotExistsFor Failed with message: " . $e->getMessage();

        }
    }

    /**
     * Tests API Connection and returns boolean
     *
     */
    public static function testAPIConnection()
    {
        try {
            $client = self::getPadClient();
            $client->listAllGroups();
            return true;
        } catch (\UnexpectedValueException $ex) {
            return false;
        } catch (\InvalidArgumentException $ex) {
            return false;
        } catch (\Exception $ex) {
            return false;
        }
    }

    /**
     * Get global note color for an user or create a new one, if not exists
     *
     * @param User $user
     * @return string
     */
    public static function getUserColor(User $user)
    {

        // get user color from db
        $userColor = NoteUserColors::findOne(['user_id' => $user->id]);

        // create a new color, if not exists
        if ($userColor == null) {

            // save new color in database
            $userColor = new NoteUserColors();
            $userColor->user_id = $user->id;
            $userColor->color = static::getRandomHexColor();

            if ($userColor->validate()) {
                $userColor->save();
            }

        }

        return $userColor->color;
    }

    /**
     * Returns a random hex color
     *
     * @return string
     */
    private static function getRandomHexColor() {
        require_once Yii::$app->getModule('notes')->basePath.'/vendors/RandomColor/src/RandomColor.php';
        return substr(\Colors\RandomColor::one(['luminosity' => 'light']), 1);
    }

    /**
     * Returns a hex color code
     *
     */
    public static function rgb2hex($rgb)
    {
        $hex = str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

        return $hex; // returns the hex value including the number sign (#)
    }


}