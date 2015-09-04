<?php

namespace humhub\modules\notes\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Html;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\notes\models\Note;
use humhub\models\Setting;

class NoteController extends ContentContainerController
{

    /**
     * @var boolean hides containers sidebar in layout
     * @since 0.11
     */
    public $hideSidebar = true;

    public function actions()
    {
        return array(
            'stream' => array(
                'class' => \humhub\modules\notes\StreamAction::className(),
                'mode' => \humhub\modules\notes\StreamAction::MODE_NORMAL,
                'contentContainer' => $this->contentContainer
            ),
        );
    }

    /**
     * Shows the questions tab
     */
    public function actionShow()
    {
        return $this->render('show', ['contentContainer' => $this->contentContainer]);
    }

    /**
     * Creates a new note via NoteFormWidget/ContentFormWidget
     */
    public function actionCreate()
    {
        $note = new Note();
        $note->title = Yii::$app->request->post('title');
        return \humhub\modules\notes\widgets\WallCreateForm::create($note);
    }

    /**
     * Shows the questions tab
     */
    public function actionOpen()
    {
        $id = (int) Yii::$app->request->get('id', 0);
        $note = Note::find()->contentContainer($this->contentContainer)->readable()->where(['note.id' => $id])->one();

        if (!$note->content->canRead()) {
            throw new HttpException(401, 'Access denied!');
        }

        $authorId = $note->getPadAuthorId();
        $groupId = $note->getPadGroupId();

        // SET ETHERPAD COOKIE
        $validUntil = mktime(0, 0, 0, date("m"), date("d") + 1, date("y")); // One day in the future
        $sessionID = $note->getEtherpadClient()->createSession($groupId, $authorId, $validUntil);
        $sessionID = $sessionID->sessionID;
        setcookie("sessionID", $sessionID, $validUntil, '/'); // Set a cookie

        $note->tryCreatePad();

        $url = Setting::Get('baseUrl', 'notes');
        $padUrl = $url . "p/" . $note->getPadNameInternal() . "?showChat=true&showLineNumbers=false&userColor=%23" . $note->getUserColor(Yii::$app->user->id);

        return $this->render('open', array(
                    'contentContainer' => $this->contentContainer,
                    'note' => $note,
                    'padUrl' => $padUrl,
                    'editors' => $note->getPadUser(),
                    'revisionCount' => $note->getRevisionCount()
        ));
    }

    public function actionEdit()
    {
        $id = (int) Yii::$app->request->get('id', 0);
        $note = Note::find()->contentContainer($this->contentContainer)->readable()->where(['note.id' => $id])->one();

        // get current revision count
        $revisionCountNow = $note->getRevisionCount();
        $revisionCountByOpening = (int) Yii::$app->request->get('revision', 0);

        /*
          if ($revisionCountNow != $revisionCountByOpening) {

          }
         */

        $this->redirect($this->contentContainer->getUrl());
    }

}
