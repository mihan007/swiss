<?php

class m121015_113942_import_data extends CDbMigration
{
	public function up()
	{
        $dataDir = Yii::getPathOfAlias('common.data');
        $dataFile = $dataDir . DIRECTORY_SEPARATOR . 'data.php';
        if (!is_file($dataFile))
            throw new CException('No data to import. Please move data for import to ' . $dataDir . " and name it data.php");

        require_once($dataFile);

        foreach ($data as $row)
        {
            $profile = new Profile();
            $profile->attributes = $row;
            if (!$profile->save())
                echo CVarDumper::dumpAsString($profile->errors);

            foreach ($row['friends'] as $friendId)
            {
                $profileHasFriend = new ProfileHasFriend();
                $profileHasFriend->id = $profile->id;
                $profileHasFriend->friend_id = $friendId;
                if (!$profileHasFriend->save())
                    echo CVarDumper::dumpAsString($profileHasFriend->errors);
            }
        }
	}

	public function down()
	{
		echo "m121015_113942_import_data does not support migration down.\n";
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