<?php

/**
 * This is the model class for table "user_follow".
 *
 * The followings are the available columns in table 'user_follow':
 * @property integer $user_follower_id
 * @property integer $user_followed_id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * The followings are the available model relations:
 * @property User $userFollower
 * @property User $userFollowed
 *
 * @package humhub.modules_core.user.models
 * @since 0.5
 * @author Luke

 */
class NoteUserColors extends HActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserFollow the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'note_usercolors';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, color, created_at, created_by, updated_at, updated_by', 'required'),
            array('created_by, updated_by', 'numerical', 'integerOnly' => true),
        );
    }

}