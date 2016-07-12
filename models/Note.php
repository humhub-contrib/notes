<?php

namespace humhub\modules\notes\models;

use Yii;
use humhub\models\Setting;
use humhub\modules\notes\libs\EtherpadLiteClient;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "note".
 *
 * The followings are the available columns in table 'note':
 * @property integer $id
 * @property string $title
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class Note extends \humhub\modules\content\components\ContentActiveRecord implements \humhub\modules\search\interfaces\Searchable
{

    public $autoAddToWall = true;
    private static $_etherClient;
    public $userColor = "d4eed4";

    /**
     * @inheritdoc
     */
    public $wallEntryClass = 'humhub\modules\notes\widgets\WallEntry';

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'note';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array(['title'], 'required'),
            array('title', 'string', 'max' => 255),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => 'Title',
            'visibility' => 'Visibility',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        );
    }

    /**
     * Gets Etherpads Group ID
     *
     * @return null
     */
    public function getPadGroupId()
    {
        try {
            $mappedGroup = $this->getEtherpadClient()->createGroupIfNotExistsFor($this->content->container->guid);
            return $mappedGroup->groupID;
        } catch (Exception $e) {
            #print_r($e);
        }

        return null;
    }

    /**
     * Gets Etherpads Author Id
     *
     * @return null
     */
    public function getPadAuthorId()
    {
        try {
            $author = $this->getEtherpadClient()->createAuthorIfNotExistsFor(Yii::$app->user->guid, Yii::$app->user->getIdentity()->displayName);
            return $author->authorID;
        } catch (Exception $e) {
            echo "\n\ncreateAuthorIfNotExistsFor Failed with message: " . $e->getMessage();
            die();
        }

        return null;
    }

    /**
     * Gets a unique ID for this Pad
     *
     * @return type
     */
    public function getPadId()
    {
        return $this->content->container->guid . "_" . $this->id;
    }

    /**
     * Returns the PadName internal used by Etherpad
     *
     * @return type
     */
    public function getPadNameInternal()
    {
        return $this->getPadGroupId() . "$" . $this->getPadId();
    }

    /**
     * Returns Content for this Pad
     *
     */
    public function getPadContent()
    {
        try {
            $content = $this->getEtherpadClient()->getText($this->getPadNameInternal());
            return $content->text;
        } catch (\Exception $ex) {
            return Yii::t('NotesModule.models_Note', "Could not get note content!");
        }
    }

    /**
     * Returns contributed user for this Pad
     *
     */
    public function getPadUser()
    {
        try {
            // get list of all pad authors
            $authors = $this->getEtherpadClient()->listAuthorsOfPad($this->getPadNameInternal());

            // save all author names in an array
            $editors = array();

            foreach ($authors->authorIDs as $authorID) {

                // load the the user within the id
                $user = User::findOne(array('username' => $this->getEtherpadClient()->getAuthorName($authorID)));

                if ($user !== null) {

                    // get (set if not exist) the user color
                    $this->userColor = $this->getUserColor($user->id);

                    // extend array with user details from profile and user model
                    array_push($editors, array('id' => $user->id, 'displayName' => $user->displayName, 'title' => $user->profile->title, 'image' => $user->getProfileImage()->getUrl(), 'url' => $user->getUrl(), 'color' => $this->userColor, 'online' => $this->getOnlineStatus($authorID)));
                }
            }

            return $editors;
        } catch (\Exception $ex) {
            Yii::error("Could not get note users! " . $ex->getMessage());
        }
        return [];
    }

    public function getRevisionCount()
    {

        $revision_count = $this->getEtherpadClient()->getRevisionsCount($this->getPadNameInternal());

        return $revision_count->revisions;
    }

    /**
     * check if an user is currently online
     *
     */
    public function getOnlineStatus($authorID)
    {

        $status = "false";

        // get all authors, which are currently online
        $authorsOnline = $this->getEtherpadClient()->padUsers($this->getPadNameInternal());

        // check if the passed author id match with an online user
        foreach ($authorsOnline->padUsers as $authorOnline) {
            if ($authorOnline->id == $authorID) {
                $status = "true";
            }
        }

        return $status;
    }

    /**
     * get global note color for an user or create a new one, if not exists
     *
     */
    public function getUserColor($id)
    {

        // get user color from db
        $query = NoteUserColors::findOne(array('user_id' => $id));

        // create a new color, if not exists
        if ($query == null) {

            // create random rgb colors in a bright color range
            $red = rand(180, 235);
            $green = rand(180, 235);
            $blue = rand(180, 235);

            $rgb = array($red, $green, $blue);

            // convert into hex code
            $hexColor = $this->rgb2hex($rgb);

            // save new color in database
            $noteUserColor = new NoteUserColors();
            $noteUserColor->user_id = $id;
            $noteUserColor->color = $hexColor;

            if ($noteUserColor->validate()) {
                $noteUserColor->save();
            }
        } else {

            // get color, if an entry exists in database for this user
            $hexColor = $query->color;
        }

        return $hexColor;
    }

    /**
     * Returns a hex color code
     *
     */
    private function rgb2hex($rgb)
    {
        $hex = str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
        $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

        return $hex; // returns the hex value including the number sign (#)
    }

    /**
     * Tries to create this etherpad if not already exists
     */
    public function tryCreatePad()
    {
        try {
            $this->getEtherpadClient()->createGroupPad($this->getPadGroupId(), $this->getPadId(), "This is a new pad!");
        } catch (\InvalidArgumentException $e) {
            
        } catch (Exception $e) {
            # already exists
        }
    }

    /**
     * Returns an instance of the Etherpad Client.
     *
     * Maybe singleton in future.
     *
     * @return type
     */
    public static function getEtherpadClient()
    {
        $apiKey = Setting::Get('apiKey', 'notes');
        $url = Setting::Get('baseUrl', 'notes');

        if (!self::$_etherClient)
            self::$_etherClient = new EtherpadLiteClient($apiKey, $url . "api");

        return self::$_etherClient;
    }

    public function getContentName()
    {
        return Yii::t('NotesModule.models_Note', "Note");
    }

    public function getContentDescription()
    {
        return $this->title;
    }

    /**
     * Tests API Connection and returns boolean
     *
     */
    public static function testAPIConnection()
    {
        try {
            $client = self::getEtherpadClient();
            $client->listAllGroups();
            return true;
        } catch (\UnexpectedValueException $ex) {
            return false;
        } catch (\InvalidArgumentException $ex) {
            return false;
        } catch (Exception $ex) {
            return false;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        return array(
            'title' => $this->title,
            'content' => $this->getPadContent(),
        );
    }

}
