<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\notes;

use yii\web\AssetBundle;

class Assets extends AssetBundle
{

    public $css = [
        'notes.css',
    ];


    public $publishOptions = [
      'forceCopy' => true
    ];

    public function init()
    {
        $this->sourcePath = dirname(__FILE__) . '/resources';
        parent::init();
    }

}
