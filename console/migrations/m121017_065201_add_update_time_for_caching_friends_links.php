<?php

class m121017_065201_add_update_time_for_caching_friends_links extends CDbMigration
{
	public function up()
	{
        $this->addColumn('profile_has_friend', 'created' ,'datetime');
        $this->addColumn('profile_has_friend', 'updated' ,'datetime');
        $sql = 'UPDATE profile_has_friend SET created = NOW()';
        $this->execute($sql);
        $sql = 'UPDATE profile_has_friend SET updated = created';
        $this->execute($sql);
        $this->createIndex('updated', 'profile_has_friend', 'updated');
    }

	public function down()
	{
		echo "m121017_065201_add_update_time_for_caching_friends_links does not support migration down.\n";
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