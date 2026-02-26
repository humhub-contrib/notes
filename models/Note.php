<?php

namespace humhub\modules\notes\models;

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\notes\libs\EtherpadHelper;
use Yii;

/**
 * This is the model class for table "note".
 *
 * The followings are the available columns in table 'note':
 * @property int $id
 * @property string $title
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 */
class Note extends ContentActiveRecord
{
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
        return [
            [['title'], 'required'],
            ['title', 'string', 'max' => 255],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'visibility' => 'Visibility',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }



    /**
     * Returns contributed user for this Pad
     *
     */
    public function getPadUser()
    {
        try {
            // get list of all pad authors
            $authors = EtherpadHelper::getPadClient()->listAuthorsOfPad($this->getPadNameInternal());
        } catch (\Exception $ex) {
            Yii::error("Could not get note users! " . $ex->getMessage());
        }

        $editors = [];
        foreach ($authors->authorIDs as $authorId) {
            $user = EtherpadHelper::getUserByEtherpadAuthorId($authorId);
            if ($user !== null) {
                // get (set if not exist) the user color
                $this->userColor = EtherpadHelper::getUserColor($user);

                // extend array with user details from profile and user model
                $editors[] = ['id' => $user->id, 'displayName' => $user->displayName, 'title' => $user->profile->title, 'image' => $user->getProfileImage()->getUrl(), 'url' => $user->getUrl(), 'color' => $this->userColor, 'online' => $this->getOnlineStatus($authorId)];
            }
        }
        return $editors;
    }

    /**
     * Returns the PadName internal used by Etherpad
     *
     * @return string pad internal name
     */
    public function getPadNameInternal()
    {
        return EtherpadHelper::getPadGroupId($this->content->container) . "$" . $this->getPadId();
    }

    /**
     * Gets a unique ID for this Pad
     *
     * @return string the pad id
     */
    public function getPadId()
    {
        return $this->content->container->guid . "_" . $this->id;
    }



    /**
     * check if an user is currently online
     */
    public function getOnlineStatus($authorID)
    {

        $status = "false";

        // get all authors, which are currently online
        $authorsOnline = EtherpadHelper::getPadClient()->padUsers($this->getPadNameInternal());

        // check if the passed author id match with an online user
        foreach ($authorsOnline->padUsers as $authorOnline) {
            if ($authorOnline->id == $authorID) {
                $status = "true";
            }
        }

        return $status;
    }


    /**
     * Returns the number of revisions
     *
     * @return int the number of revisions
     */
    public function getRevisionCount()
    {
        $revision_count = EtherpadHelper::getPadClient()->getRevisionsCount($this->getPadNameInternal());
        return $revision_count->revisions;
    }

    /**
     * Tries to create this etherpad if not already exists
     */
    public function tryCreatePad()
    {
        try {
            EtherpadHelper::getPadClient()->createGroupPad(EtherpadHelper::getPadGroupId($this->content->container), $this->getPadId(), "This is a new pad!");
        } catch (\Exception) {
            # already exists
        }
    }

    /**
     * @inheritdoc
     */
    public function getContentName()
    {
        return Yii::t('NotesModule.base', "Note");
    }

    /**
     * @inheritdoc
     */
    public function getContentDescription()
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        return [
            'title' => $this->title,
            'content' => $this->getPadContent(),
        ];
    }

    /**
     * Returns Content for this Pad
     *
     * @return string the pad text
     */
    public function getPadContent()
    {
        try {
            $content = EtherpadHelper::getPadClient()->getText($this->getPadNameInternal());
            return $content->text;
        } catch (\Exception) {
            return '';
            //return Yii::t('NotesModule.base', "Could not get note content!");
        }
    }

}
