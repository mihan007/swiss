<?php

class m121015_112930_create_table_for_data extends CDbMigration
{
	public function up()
	{
        $this->createTable('profile', array(
            'id' => 'pk',
            'firstName' => 'string',
            'surname' => 'string',
            'age' => 'integer',
            'gender' => 'varchar(10)'
        ));
        $this->createIndex('genderIndex', 'profile', 'gender');
	}

	public function down()
	{
		echo "m121015_112930_create_table_for_data does not support migration down.\n";
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