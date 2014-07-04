<?php

class NoteController extends Controller
{

    public $subLayout = "_layout";

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow authenticated user
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Add mix-ins to this model
     *
     * @return type
     */
    public function behaviors()
    {
        return array(
            'SpaceControllerBehavior' => array(
                'class' => 'application.modules_core.space.behaviors.SpaceControllerBehavior',
            ),
        );
    }

    /**
     * Actions
     *
     * @return type
     */
    public function actions()
    {
        return array(
            'stream' => array(
                'class' => 'application.modules.notes.NoteStreamAction',
                'mode' => 'normal',
            ),
        );
    }

    /**
     * Shows the questions tab
     */
    public function actionShow()
    {
        $this->render('show');
    }

    /**
     * Creates a new note via NoteFormWidget/ContentFormWidget
     */
    public function actionCreate()
    {

        $this->forcePostRequest();
        $_POST = Yii::app()->input->stripClean($_POST);

        $note = new Note();
        $note->content->populateByForm();
        $note->title = Yii::app()->request->getParam('title');

        if ($note->validate()) {
            $note->save();
            $this->renderJson(array('wallEntryId' => $note->content->getFirstWallEntryId()));
        } else {
            $this->renderJson(array('errors' => $note->getErrors()), false);
        }
    }

    /**
     * Shows the questions tab
     */
    public function actionOpen()
    {

        // publish css file to assets
        $url = Yii::app()->getAssetManager()->publish(
            Yii::getPathOfAlias('application.modules.notes.resources'));

        // register css file
        Yii::app()->clientScript->registerCssFile($url . '/notes.css');

        $workspace = $this->getSpace();

        $id = (int)Yii::app()->request->getParam('id', 0);
        $note = Note::model()->findByPk($id);

        if ($note->content->canRead()) {

            $authorId = $note->getPadAuthorId();
            $groupId = $note->getPadGroupId();

            // SET ETHERPAD COOKIE
            $validUntil = mktime(0, 0, 0, date("m"), date("d") + 1, date("y")); // One day in the future
            $sessionID = $note->getEtherpadClient()->createSession($groupId, $authorId, $validUntil);
            $sessionID = $sessionID->sessionID;
            setcookie("sessionID", $sessionID, $validUntil, '/'); // Set a cookie

            $note->tryCreatePad();

            $url = HSetting::Get('baseUrl', 'notes');

            // get pad users
            $editors = $note->getPadUser();

            // get revision count for this pad
            $revision = $note->getRevisionCount();

            // View
            $padUrl = $url . "p/" . $note->getPadNameInternal() . "?showChat=true&showLineNumbers=false&userColor=%23" . $note->getUserColor(Yii::app()->user->id);

            $this->render('open', array('workspace' => $workspace, 'note' => $note, 'padUrl' => $padUrl, 'editors' => $editors, 'revision' => $revision));
        } else {
            throw new CHttpException(401, 'Access denied!');
        }
    }



    public function actionEdit()
    {

        // get note id and load from database
        $id = (int)Yii::app()->request->getParam('id', 0);
        $note = Note::model()->findByPk($id);

        // get current revision count
        $revisionCountNow = $note->getRevisionCount();

        // get revision count by opening
        $revisionCountByOpening = (int)Yii::app()->request->getParam('revision', 0);

        // match revisions
        if ($revisionCountNow != $revisionCountByOpening) {

            // create activity
            $note->createUpdateActivity();

            // send notifications to other authors, if changes was made
            $note->notifyUserForUpdates();

        }
        // Redirect to the the space
        $this->htmlRedirect($this->createUrl('//space/space', array('sguid' => Yii::app()->request->getParam('guid'))));
        //echo "Juhu";

    }

    /*
      public function actionAdmin() {

      print "<pre>";
      $client = new EtherpadLiteClient(Yii::app()->getModule('notes')->etherPad_apiKey, Yii::app()->getModule('notes')->etherPad_baseUrl . "api");

      print_r($client->listAllGroups());

      print_r($client->listPads('g.oiPowCyo51TSXdbZ'));
      }
     */
}
