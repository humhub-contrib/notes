<?php

namespace humhub\modules\notes\controllers;

use humhub\models\Setting;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\notes\libs\EtherpadHelper;
use humhub\modules\notes\models\Note;
use humhub\modules\notes\permissions\CreateNote;
use humhub\modules\notes\StreamAction;
use humhub\modules\notes\widgets\WallCreateForm;
use Yii;
use yii\web\HttpException;

class NoteController extends ContentContainerController
{

    /**
     * @var boolean hides containers sidebar in layout
     * @since 0.11
     */
    public $hideSidebar = true;


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return array(
            'stream' => array(
                'class' => StreamAction::className(),
                'mode' => StreamAction::MODE_NORMAL,
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
     * @throws HttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        if (!$this->contentContainer->permissionManager->can(new CreateNote())) {
            throw new HttpException(400, 'Access denied!');
        }

        $note = new Note();
        $note->title = Yii::$app->request->post('title');
        return WallCreateForm::create($note, $this->contentContainer);
    }

    /**
     * Shows the questions tab
     * @throws HttpException
     * @throws \yii\base\Exception
     */
    public function actionOpen()
    {
        $id = (int)Yii::$app->request->get('id', 0);
        $note = Note::find()->contentContainer($this->contentContainer)->readable()->where(['note.id' => $id])->one();

        if (!$note->content->canRead()) {
            throw new HttpException(401, 'Access denied!');
        }

        // SET ETHERPAD COOKIE
        $validUntil = mktime(0, 0, 0, date("m"), date("d") + 1, date("y")); // One day in the future
        $sessionID = EtherpadHelper::getPadClient()->createSession(EtherpadHelper::getPadGroupId($this->contentContainer), EtherpadHelper::getPadAuthorId(), $validUntil);
        $sessionID = $sessionID->sessionID;
        setcookie("sessionID", $sessionID, $validUntil, '/'); // Set a cookie

        $note->tryCreatePad();

        $url = Setting::Get('baseUrl', 'notes');
        $padUrl = $url . "p/" . $note->getPadNameInternal() . "?showChat=true&showLineNumbers=false&userColor=%23" . EtherpadHelper::getUserColor(Yii::$app->user->getIdentity());

        return $this->render('open', array(
            'contentContainer' => $this->contentContainer,
            'note' => $note,
            'padUrl' => $padUrl,
            'editors' => $note->getPadUser(),
            'revisionCount' => $note->getRevisionCount()
        ));
    }

    /**
     * @throws \yii\base\Exception
     */
    public function actionEdit()
    {
        $id = (int)Yii::$app->request->get('id', 0);
        $note = Note::find()->contentContainer($this->contentContainer)->readable()->where(['note.id' => $id])->one();

        // get current revision count
        /*
        $revisionCountNow = $note->getRevisionCount();
        $revisionCountByOpening = (int)Yii::$app->request->get('revision', 0);
          if ($revisionCountNow != $revisionCountByOpening) {

          }
        */

        $this->redirect($this->contentContainer->getUrl());
    }

}
