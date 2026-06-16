<?php

use humhub\components\Migration;
use humhub\modules\notes\models\Note;

class m150726_182212_namespace extends Migration
{
    public function up()
    {
        $this->renameClass('Note', Note::class);

        $this->delete('notification', ['class' => 'NoteCreatedNotification']);
        $this->delete('notification', ['class' => 'NoteUpdatedNotification']);

        if ($this->columnExists('module', 'activity')) {
            $this->execute("DELETE FROM activity WHERE module = 'notes'");
        }
    }

    public function down()
    {
        echo "m150726_182212_namespace cannot be reverted.\n";

        return false;
    }
}
