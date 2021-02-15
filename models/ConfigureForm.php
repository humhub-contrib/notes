<?php

namespace humhub\modules\notes\models;

use Yii;
use yii\base\Model;

class ConfigureForm extends Model
{

    public $baseUrl;
    public $apiKey;
    public $epAuthSessionPlugin;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            [['apiKey', 'baseUrl'], 'required'],
            [['epAuthSessionPlugin'], 'boolean'],
            ['apiKey', 'string', 'max' => 250],
        ];
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'baseUrl' => Yii::t('NotesModule.base', 'URL to Etherpad'),
            'apiKey' => Yii::t('NotesModule.base', 'Etherpad API Key'),
            'epAuthSessionPlugin' => Yii::t('NotesModule.base', 'Use Etherpad Plugin: ep_auth_session'),
        );
    }

    public function attributeHints()
    {
        return [
            'baseUrl' => Yii::t('NotesModule.base', 'e.g. http://yourdomain/pad/'),
            'apiKey' => '',
        ];
    }
}
