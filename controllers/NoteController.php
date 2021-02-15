<?php

namespace humhub\modules\notes\controllers;

use humhub\models\Setting;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\notes\libs\EtherpadHelper;
use humhub\modules\notes\models\Note;
use humhub\modules\notes\Module;
use humhub\modules\notes\permissions\CreateNote;
use humhub\modules\notes\StreamAction;
use humhub\modules\notes\widgets\WallCreateForm;
use Yii;
use yii\web\HttpException;

/**
 * Class NoteController
 *
 * @property Module $module
 * @package humhub\modules\notes\controllers
 */
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
                'includes' => Note::className(),
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


        /** @var Note $note */
        $note = Note::find()->contentContainer($this->contentContainer)->readable()->where(['note.id' => $id])->one();

        if (!$note->content->canView()) {
            throw new HttpException(401, 'Access denied!');
        }

        // SET ETHERPAD COOKIE
        $validUntil = mktime(0, 0, 0, date("m"), date("d") + 1, date("y")); // One day in the future
        $sessionID = EtherpadHelper::getPadClient()->createSession(EtherpadHelper::getPadGroupId($this->contentContainer), EtherpadHelper::getPadAuthorId(), $validUntil);
        $sessionID = $sessionID->sessionID;

        $domain = substr(yii\helpers\Url::base(''), 2);
        if (strpos($domain, '/') !== false) {
            $domain = substr($domain, 0, strpos($domain, '/'));
        }

        // reduce domain from humhub.xyz.com to xyz.com
        $domainParts = array_reverse(explode('.', $domain));
        if (count($domainParts) > 2) {
            $domain = $domainParts[1] . '.' . $domainParts[0];
        }

        setcookie("sessionID", $sessionID, $validUntil, '/', $domain);

        $note->tryCreatePad();

        $url = $this->module->settings->get('baseUrl');

        if (!empty($this->module->settings->get('epAuthSessionPlugin'))) {
            // Use ep_auth_session plugin
            $padUrl = $url . "auth_session?sessionID=" . $sessionID . "&padName=" . $note->getPadNameInternal();
        } else {
            $padUrl = $url . "p/" . $note->getPadNameInternal() . "?sessionID=" . $sessionID . "&showChat=true&showLineNumbers=false&userColor=%23" . EtherpadHelper::getUserColor(Yii::$app->user->getIdentity());
        }

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
