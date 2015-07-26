<?php

class m140418_172839_note_usercolors extends yii\db\Migration
{
	public function up()
	{
        $this->createTable('note_usercolors', array(
            'id' => 'pk',
            'user_id' => 'int NOT NULL',
            'color' => 'text',
            'created_at' => 'datetime NOT NULL',
            'created_by' => 'int(11) NOT NULL',
            'updated_at' => 'datetime NOT NULL',
            'updated_by' => 'int(11) NOT NULL',
        ));
	}

	public function down()
	{
        $this->dropTable('note_usercolors');
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