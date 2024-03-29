<?php

/**
 * This is the model class for table "profile".
 *
 * The followings are the available columns in table 'profile':
 * @property integer $id
 * @property string $firstName
 * @property string $surname
 * @property integer $age
 * @property string $gender
 */
class Profile extends CActiveRecord
{
    const PAGE_SIZE = 25;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Profile the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'profile';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('age', 'numerical', 'integerOnly' => true),
            array('firstName, surname', 'length', 'max' => 255),
            array('gender', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, firstName, surname, age, gender', 'safe', 'on' => 'search'),
        );
    }

    public function behaviors()
    {
        return array(
            //we are using this mixin to automatically set/update time while updating db row of model
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created',
                'updateAttribute' => 'updated',
                'setUpdateOnCreate' => true
            )
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Select user',
            'firstName' => 'First Name',
            'surname' => 'Surname',
            'age' => 'Age',
            'gender' => 'Gender',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('firstName', $this->firstName, true);
        $criteria->compare('surname', $this->surname, true);
        $criteria->compare('age', $this->age);
        $criteria->compare('gender', $this->gender, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * get full name of profile
     * @return string
     */
    public function getFullName()
    {
        $fullName = array();
        if (!empty($this->surname))
            $fullName[] = $this->surname;
        if (!empty($this->firstName))
            $fullName[] = $this->firstName;
        return implode(' ', $fullName);
    }

    /**
     * get list of all existing profile inside system
     * @return array
     */
    static public function getAllPossible()
    {
        //we are using dependency to invalidate cache as only one of records will be changed/deleted
        $dependency = new CDbCacheDependency('SELECT AVG(updated) FROM profile');
        $models = self::model()->cache(param('cache.duration'), $dependency)->findAll(array('select' => array('id', 'surname', 'firstName')));
        return CHtml::listData($models, 'id', 'fullName');
    }

    /**
     * Method to get all direct friends data provider for current profile model
     * @return CActiveDataProvider
     */
    public function getDirectFriends()
    {
        $models = $this->getDirectFriendsModels();

        $friendCriteria = new CDbCriteria();
        $friendCriteria->addInCondition('id', array_keys($models));

        $dataProvider = new CActiveDataProvider('Profile', array(
            'criteria' => $friendCriteria,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE //using this to show more than common data provider's items at page
            )
        ));
        return $dataProvider;
    }

    /**
     * Method to get array of relationships of direct friends
     * @return ProfileHasFriend[]
     */
    protected function getDirectFriendsModels()
    {
        // generating criteria to search
        $criteria = new CDbCriteria();
        $criteria->select = array('friend_id');
        $criteria->distinct = true;
        $criteria->index = 'friend_id';
        $criteria->compare('id', $this->id);

        $models = ProfileHasFriend::model()
            ->findAll($criteria);
        return $models;
    }

    /**
     * Method to get all friends of friends data provider for current profile model
     * @return CActiveDataProvider
     */
    public function getFriendsOfFriends()
    {
        $models = $this->getDirectFriendsModels();
        $directFriendsIds = array_keys($models);

        //search direct friends of friends
        $friendCriteria = new CDbCriteria();
        $friendCriteria->distinct = true;
        $friendCriteria->index = 'friend_id';
        $friendCriteria->addInCondition('id', $directFriendsIds);
        $friendCriteria->addCondition('friend_id != :profile_id'); //we should exclude user a friend of himself
        $friendCriteria->addNotInCondition('friend_id', $directFriendsIds); //we shouldn't mention direct friends
        $friendCriteria->params = CMap::mergeArray($friendCriteria->params, array(
            ':profile_id' => $this->id
        ));

        $friendsOfFriends = ProfileHasFriend::model()
            ->findAll($friendCriteria);

        //we got all friends of friends ids. Now we should get all info about them
        $friendOfFriendsCriteria = new CDbCriteria();
        $friendOfFriendsCriteria->addInCondition('id', array_keys($friendsOfFriends));

        $dataProvider = new CActiveDataProvider('Profile', array(
            'criteria' => $friendOfFriendsCriteria,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE
            )
        ));
        return $dataProvider;
    }

    /**
     * Get all suggested friend data provider of current model
     * @return CActiveDataProvider
     */
    public function getSuggestedFriends()
    {
        //some tricky sql to get ids of suggested friends
        $sql = '
            SELECT id, COUNT(*) AS cnt FROM profile_has_friend f1 WHERE f1.friend_id IN
            (SELECT friend_id FROM profile_has_friend f2 WHERE f2.id = :uid)
            AND f1.id NOT IN
            (SELECT friend_id FROM profile_has_friend f3 WHERE f3.id = :uid)
            AND id != :uid2
            GROUP BY id
            HAVING cnt>=2
        ';

        $command = Yii::app()->db
            ->createCommand($sql);

        //it's annoying feature(bug?) inside PDO (http://php.net/manual/en/pdo.prepare.php)
        $command->params = array(
            ':uid' => $this->id,
            ':uid2' => $this->id,
        );

        $ids = $command->queryColumn();
        //we got ids of suggested friends above. now we'll get all info about them
        $suggestedFriend = new CDbCriteria();
        $suggestedFriend->addInCondition('id', $ids);

        $dataProvider = new CActiveDataProvider('Profile', array(
            'criteria' => $suggestedFriend,
            'pagination' => array(
                'pageSize' => self::PAGE_SIZE
            )
        ));
        return $dataProvider;
    }
}