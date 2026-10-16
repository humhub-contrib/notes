<?php

namespace humhub\modules\notes\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\notes\actions\StreamAction;
use humhub\modules\notes\libs\EtherpadHelper;
use humhub\modules\notes\models\Note;
use humhub\modules\notes\Module;
use humhub\modules\notes\permissions\CreateNote;
use humhub\modules\notes\widgets\WallCreateForm;
use Yii;
use yii\helpers\Url;
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
     * @var bool hides containers sidebar in layout
     * @since 0.11
     */
    public $hideSidebar = true;


    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'stream' => [
                'class' => StreamAction::class,
                'includes' => Note::class,
                'contentContainer' => $this->contentContainer,
            ],
        ];
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
            throw new HttpException(403, 'Access denied!');
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

        if ($note === null) {
            throw new HttpException(404, Yii::t('NotesModule.base', 'Note not found!'));
        }

        if (!$note->content->canView()) {
            throw new HttpException(403, 'Access denied!');
        }

        if (!EtherpadHelper::isConfigured()) {
            throw new HttpException(503, Yii::t('NotesModule.base', 'Etherpad is not configured. Please contact your administrator.'));
        }

        // SET ETHERPAD COOKIE
        try {
            $validUntil = mktime(0, 0, 0, date("m"), date("d") + 1, date("y")); // One day in the future
            $groupId = EtherpadHelper::getPadGroupId($this->contentContainer);
            $authorId = EtherpadHelper::getPadAuthorId();
            if ($groupId === null || $authorId === null) {
                throw new \Exception('Could not resolve Etherpad group or author');
            }

            $sessionID = EtherpadHelper::getPadClient()->createSession($groupId, $authorId, $validUntil)?->sessionID;
            if (empty($sessionID)) {
                throw new \Exception('Etherpad returned no session');
            }

            $note->tryCreatePad();
        } catch (\Exception $e) {
            Yii::error('Could not open Etherpad session: ' . $e->getMessage(), 'notes');
            throw new HttpException(503, Yii::t('NotesModule.base', 'Etherpad is currently not available. Please try again later.'));
        }

        $domain = (string) parse_url(Url::base(true), PHP_URL_HOST);

        // reduce domain from humhub.xyz.com to xyz.com (not for IP addresses)
        if (!filter_var($domain, FILTER_VALIDATE_IP)) {
            $domainParts = array_reverse(explode('.', $domain));
            if (count($domainParts) > 2) {
                $domain = $domainParts[1] . '.' . $domainParts[0];
            }
        }

        setcookie("sessionID", (string) $sessionID, [
            'expires' => $validUntil,
            'path' => '/',
            'domain' => $domain,
            'secure' => Yii::$app->request->isSecureConnection,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        $url = EtherpadHelper::getBaseUrl();

        if (!empty($this->module->settings->get('epAuthSessionPlugin'))) {
            // Use ep_auth_session plugin
            $padUrl = $url . "auth_session?sessionID=" . rawurlencode($sessionID) . "&padName=" . rawurlencode($note->getPadNameInternal());
        } else {
            $padUrl = $url . "p/" . $note->getPadNameInternal() . "?sessionID=" . rawurlencode($sessionID) . "&showChat=true&showLineNumbers=false&userColor=%23" . EtherpadHelper::getUserColor(Yii::$app->user->getIdentity());
        }

        return $this->render('open', [
            'contentContainer' => $this->contentContainer,
            'note' => $note,
            'padUrl' => $padUrl,
            'editors' => $note->getPadUser(),
            'revisionCount' => $note->getRevisionCount(),
        ]);
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
