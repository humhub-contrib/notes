<?php

namespace humhub\modules\notes\actions;

use humhub\modules\notes\models\Note;
use humhub\modules\content\actions\ContentContainerStream;

class StreamAction extends ContentContainerStream
{
    public function setupFilters()
    {
        $this->activeQuery->andWhere(['content.object_model' => Note::class]);
    }

}
