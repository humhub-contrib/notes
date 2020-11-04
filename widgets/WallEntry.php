<?php

namespace humhub\modules\notes\widgets;

use humhub\modules\content\widgets\stream\WallStreamModuleEntryWidget;
use humhub\modules\notes\models\Note;

class WallEntry extends WallStreamModuleEntryWidget
{
    /**
     * @var Note
     */
    public $model;


    /**
     * @inheritDoc
     */
    protected function renderContent()
    {
        return $this->render('entry', array(
            'note' => $this->model,
            'contentContainer' => $this->model->content->container));
    }

    /**
     * @inheritDoc
     */
    protected function getTitle()
    {
        return $this->model->title;
    }
}

?>