<?php

class m121017_072347_add_update_time_for_caching_profiles extends CDbMigration
{
	public function up()
	{
        $this->addColumn('profile', 'created' ,'datetime');
        $this->addColumn('profile', 'updated' ,'datetime');
        $sql = 'UPDATE profile SET created = NOW()';
        $this->execute($sql);
        $sql = 'UPDATE profile SET updated = created';
        $this->execute($sql);
        $this->createIndex('updated', 'profile', 'updated');
	}

	public function down()
	{
		echo "m121017_072347_add_update_time_for_caching_profiles does not support migration down.\n";
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