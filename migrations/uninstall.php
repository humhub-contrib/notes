<?php

class uninstall extends yii\db\Migration
{

    public function up()
    {

        $this->dropTable('note');
        $this->dropTable('note_usercolors');
    }

    public function down()
    {
        echo "m131023_165956_initial does not support migration down.\n";
        return false;
    }

}
