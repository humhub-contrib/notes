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
class Note extends HActiveRecordContent {

    private static $_etherClient;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Note the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'note';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
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
    public function attributeLabels() {
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
    public function getPadGroupId() {
        $contentBase = $this->contentMeta->getContentBase();
        try {
            $mappedGroup = $this->getEtherpadClient()->createGroupIfNotExistsFor($contentBase->guid);
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
    public function getPadAuthorId() {
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
    public function getPadId() {
        $contentBase = $this->contentMeta->getContentBase();
        return $contentBase->guid . "_" . $this->id;
    }

    /**
     * Returns the PadName internal used by Etherpad
     *
     * @return type
     */
    public function getPadNameInternal() {
        return $this->getPadGroupId() . "$" . $this->getPadId();
    }

    /**
     * Returns Content for this Pad
     *
     */
    public function getPadContent() {
        try {
            $content = $this->getEtherpadClient()->getText($this->getPadNameInternal());
            return $content->text;
        } catch (Exception $ex) {
            return Yii::t('NotesModule.base', "Could not get note content!");
        }
    }

    /**
     * Tries to create this etherpad if not already exists
     */
    public function tryCreatePad() {
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
    public static function getEtherpadClient() {
        $apiKey = HSetting::Get('apiKey', 'notes');
        $url = HSetting::Get('baseUrl', 'notes');

        if (!self::$_etherClient)
            self::$_etherClient = new EtherpadLiteClient($apiKey, $url . "api");

        return self::$_etherClient;
    }

    /**
     * Returns the Wall Output
     */
    public function getWallOut() {
        return Yii::app()->getController()->widget('application.modules.notes.widgets.NoteWallEntryWidget', array('note' => $this), true);
    }

    /**
     * Returns a title/text which identifies this IContent.
     *
     * e.g. Post: foo bar 123...
     *
     * @return String
     */
    public function getContentTitle() {
        return Yii::t('NotesModule.base', "Note") . " \"" . Helpers::truncateText($this->title, 25) . "\"";
    }

    /**
     * Tests API Connection and returns boolean
     *
     */
    public static function testAPIConnection() {


        try {
            $client = self::getEtherpadClient();
            $client->listAllGroups();
            return true;
        } catch (Exception $ex) {
            return false;
        }

        return false;
    }

}