<?php

namespace humhub\modules\notes\models;

use Yii;
use yii\base\Model;

class ConfigureForm extends Model
{

    public $baseUrl;
    public $apiKey;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array(['apiKey', 'baseUrl'], 'required'),
            array('apiKey', 'string', 'max' => 250),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'baseUrl' => Yii::t('NotesModule.forms_NotesConfigureForm', 'URL to Etherpad'),
            'apiKey' => Yii::t('NotesModule.forms_NotesConfigureForm', 'Etherpad API Key'),
        );
    }

}
