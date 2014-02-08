<?php

class NotesConfigureForm extends CFormModel {

    public $baseUrl;
    public $apiKey;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('apiKey, baseUrl', 'required'),
            array('apiKey', 'length', 'max' => 250),

            //array('baseUrl', 'url'),      // dont work with http://localhsot/..
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'baseUrl' => Yii::t('NotesModule.base', 'URL to Etherpad'),
            'apiKey' => Yii::t('NotesModule.base', 'Etherpad API Key'),
        );
    }

}