<?php

namespace humhub\modules\notes\widgets;

use humhub\components\Widget;

class WallEntry extends Widget
{

    public $note;

    public function run()
    {
        return $this->render('entry', array(
                    'note' => $this->note,
                    'user' => $this->note->content->user,
                    'contentContainer' => $this->note->content->container));
    }

}

?>