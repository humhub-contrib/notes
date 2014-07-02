<?php

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
class Note extends HActiveRecordContent
{

    public $autoAddToWall = true;
    private static $_etherClient;
    public $userColor = "d4eed4";

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Note the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'note';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, created_at, created_by, updated_at, updated_by', 'required'),
            array('created_by, updated_by', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
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
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'answers' => array(self::HAS_MANY, 'PollAnswer', 'poll_id'),
        );
    }

    public function afterSave()
    {
        parent::afterSave();

        if ($this->isNewRecord) {
            // Create Note created activity
            $activity = Activity::CreateForContent($this);
            $activity->type = "NoteCreated";
            $activity->module = "notes";
            $activity->save();
            $activity->fire();
        }

        return true;
    }

    /**
     * Deletes a Poll including its dependencies.
     */
    public function beforeDelete()
    {

        // delete notification
        Notification::remove('Note', $this->id);

        return parent::beforeDelete();
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
            $author = $this->getEtherpadClient()->createAuthorIfNotExistsFor(Yii::app()->user->guid, Yii::app()->user->displayName);
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
        } catch (Exception $ex) {
            return Yii::t('NotesModule.base', "Could not get note content!");
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
                $user = User::model()->findByAttributes(array('username' => $this->getEtherpadClient()->getAuthorName($authorID)));

                if ($user !== null) {

                    // get (set if not exist) the user color
                    $this->userColor = $this->getUserColor($user->id);

                    // extend array with user details from profile and user model
                    array_push($editors, array('id' => $user->id, 'displayName' => $user->displayName, 'title' => $user->profile->title, 'image' => $user->getProfileImage()->getUrl(), 'url' => $user->getProfileUrl(), 'color' => $this->userColor, 'online' => $this->getOnlineStatus($authorID)));
                }
            }

            return $editors;
        } catch (Exception $ex) {
            return Yii::t('NotesModule.base', "Could not get note users!");
        }
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
        $query = NoteUserColors::model()->findByAttributes(array('user_id' => $id));

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
        } catch (Exception $e) {
            # already exists
            # print_r($e);
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
        $apiKey = HSetting::Get('apiKey', 'notes');
        $url = HSetting::Get('baseUrl', 'notes');

        if (!self::$_etherClient)
            self::$_etherClient = new EtherpadLiteClient($apiKey, $url . "api");

        return self::$_etherClient;
    }

    /**
     * Returns the Wall Output
     */
    public function getWallOut()
    {
        return Yii::app()->getController()->widget('application.modules.notes.widgets.NoteWallEntryWidget', array('note' => $this), true);
    }

    /**
     * Returns a title/text which identifies this IContent.
     *
     * e.g. Post: foo bar 123...
     *
     * @return String
     */
    public function getContentTitle()
    {
        return Yii::t('NotesModule.base', "Note") . " \"" . Helpers::truncateText($this->title, 25) . "\"";
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
        } catch (Exception $ex) {
            return false;
        }

        return false;
    }

    /**
     * Send notifications for updates to a pad
     */
    public function notifyUserForUpdates()
    {

        // get pad authors
        $authors = $this->getPadUser();

        foreach ($authors as $author) {

            // save current user
            $currentUser = Yii::app()->user->id;

            // Don't send notification to the current user who did the changes
            if ($author['id'] != $currentUser) {

                // Fire Notification to user
                $notification = new Notification();
                $notification->class = "NoteUpdatedNotification";
                $notification->user_id = $author['id']; // Assigned User
                $notification->space_id = $this->content->space_id;
                $notification->source_object_model = 'Note';
                $notification->source_object_id = $this->id;
                $notification->target_object_model = 'Note';
                $notification->target_object_id = $this->id;
                $notification->save();
            }
        }
    }

    public function createUpdateActivity()
    {

        // Create Note updated activity
        $activity = Activity::CreateForContent($this);
        $activity->type = "NoteUpdated";
        $activity->module = "notes";
        $activity->save();
        $activity->fire();
    }

}
