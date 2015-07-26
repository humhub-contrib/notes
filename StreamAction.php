<?php

namespace humhub\modules\notes;

use humhub\modules\content\components\actions\ContentContainerStream;
use humhub\modules\notes\models\Note;

class StreamAction extends ContentContainerStream
{

    public function setupFilters()
    {
        $this->activeQuery->andWhere(['content.object_model' => Note::className()]);
    }

}

?>
