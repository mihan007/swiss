<?php

class m121015_112725_user_for_test extends CDbMigration
{
	public function up()
	{
        $testUser = new User();
        $testUser->username = "test";
        $testUser->email = "test@test.com";
        $testUser->password = "SomePassw0rd";
        $testUser->save();
    }

	public function down()
	{
		echo "m121015_112725_user_for_test does not support migration down.\n";
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