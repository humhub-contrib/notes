<?php

namespace humhub\modules\notes\widgets;

class WallEntry extends \humhub\modules\content\widgets\WallEntry
{

    public function run()
    {
        return $this->render('entry', array(
                    'note' => $this->contentObject,
                    'contentContainer' => $this->contentObject->content->container));
    }

}

?>