<?php

use yii\db\Schema;
use humhub\components\Migration;
use humhub\modules\notes\models\Note;

class m150726_182212_namespace extends Migration
{

    public function up()
    {
        $this->renameClass('Note', Note::className());

        $this->delete('notification', ['class' => 'NoteCreatedNotification']);
        $this->delete('notification', ['class' => 'NoteUpdatedNotification']);
        
        foreach (\humhub\modules\activity\models\Activity::findAll(['module'=>'notes']) as $activity) {
            $activity->delete();
        }
        
    }

    public function down()
    {
        echo "m150726_182212_namespace cannot be reverted.\n";

        return false;
    }

    /*
      // Use safeUp/safeDown to run migration code within a transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
