<?php

class m131023_165956_initial extends yii\db\Migration {

    public function up() {
        $this->createTable('note', array(
            'id' => 'pk',
            'title' => 'varchar(255) NOT NULL',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
                ), '');
    }

    public function down() {
        echo "m131023_165956_initial does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}