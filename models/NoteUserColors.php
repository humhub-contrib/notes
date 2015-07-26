<?php

namespace humhub\modules\notes\models;

class NoteUserColors extends \humhub\components\ActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'note_usercolors';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array(['user_id', 'color'], 'required'),
        );
    }

}
