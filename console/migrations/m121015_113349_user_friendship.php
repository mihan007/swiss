<?php

class m121015_113349_user_friendship extends CDbMigration
{
	public function up()
	{
        $this->createTable('profile_has_friend', array(
            'id' => 'integer',
            'friend_id' => 'integer',
            'PRIMARY KEY (id, friend_id)'
        ));
	}

	public function down()
	{
		echo "m121015_113349_user_friendship does not support migration down.\n";
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