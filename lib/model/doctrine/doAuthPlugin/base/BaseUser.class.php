<?php

/**
 * BaseUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $username
 * @property string $first_name
 * @property string $second_name
 * @property string $middle_name
 * @property string $gender
 * @property timestamp $birth_date
 * @property string $email
 * @property string $phone
 * @property string $salt
 * @property string $password
 * @property string $photo
 * @property boolean $is_active
 * @property boolean $is_super_admin
 * @property timestamp $last_login
 * @property string $password_check
 * @property Doctrine_Collection $UserPermissions
 * @property Doctrine_Collection $UserGroup
 * @property Doctrine_Collection $UserLog
 * @property Doctrine_Collection $Session
 * @property Doctrine_Collection $AgreementComplete
 * @property Doctrine_Collection $Specialist
 * @property Doctrine_Collection $Review
 * @property Doctrine_Collection $Comment
 * @property Doctrine_Collection $Question
 * @property Doctrine_Collection $Reception_contract
 * @property Doctrine_Collection $Answer
 * @property Doctrine_Collection $Analysis
 * @property Doctrine_Collection $Feedback
 * @property Doctrine_Collection $Notice
 * @property Doctrine_Collection $Attached_family_users
 * @property Doctrine_Collection $Message_error
 * @property Doctrine_Collection $Token
 * 
 * @method string              getUsername()              Returns the current record's "username" value
 * @method string              getFirstName()             Returns the current record's "first_name" value
 * @method string              getSecondName()            Returns the current record's "second_name" value
 * @method string              getMiddleName()            Returns the current record's "middle_name" value
 * @method string              getGender()                Returns the current record's "gender" value
 * @method timestamp           getBirthDate()             Returns the current record's "birth_date" value
 * @method string              getEmail()                 Returns the current record's "email" value
 * @method string              getPhone()                 Returns the current record's "phone" value
 * @method string              getSalt()                  Returns the current record's "salt" value
 * @method string              getPassword()              Returns the current record's "password" value
 * @method string              getPhoto()                 Returns the current record's "photo" value
 * @method boolean             getIsActive()              Returns the current record's "is_active" value
 * @method boolean             getIsSuperAdmin()          Returns the current record's "is_super_admin" value
 * @method timestamp           getLastLogin()             Returns the current record's "last_login" value
 * @method string              getPasswordCheck()         Returns the current record's "password_check" value
 * @method Doctrine_Collection getUserPermissions()       Returns the current record's "UserPermissions" collection
 * @method Doctrine_Collection getUserGroup()             Returns the current record's "UserGroup" collection
 * @method Doctrine_Collection getUserLog()               Returns the current record's "UserLog" collection
 * @method Doctrine_Collection getSession()               Returns the current record's "Session" collection
 * @method Doctrine_Collection getAgreementComplete()     Returns the current record's "AgreementComplete" collection
 * @method Doctrine_Collection getSpecialist()            Returns the current record's "Specialist" collection
 * @method Doctrine_Collection getReview()                Returns the current record's "Review" collection
 * @method Doctrine_Collection getComment()               Returns the current record's "Comment" collection
 * @method Doctrine_Collection getQuestion()              Returns the current record's "Question" collection
 * @method Doctrine_Collection getReceptionContract()     Returns the current record's "Reception_contract" collection
 * @method Doctrine_Collection getAnswer()                Returns the current record's "Answer" collection
 * @method Doctrine_Collection getAnalysis()              Returns the current record's "Analysis" collection
 * @method Doctrine_Collection getFeedback()              Returns the current record's "Feedback" collection
 * @method Doctrine_Collection getNotice()                Returns the current record's "Notice" collection
 * @method Doctrine_Collection getAttachedFamilyUsers()   Returns the current record's "Attached_family_users" collection
 * @method Doctrine_Collection getMessageError()          Returns the current record's "Message_error" collection
 * @method Doctrine_Collection getToken()                 Returns the current record's "Token" collection
 * @method User                setUsername()              Sets the current record's "username" value
 * @method User                setFirstName()             Sets the current record's "first_name" value
 * @method User                setSecondName()            Sets the current record's "second_name" value
 * @method User                setMiddleName()            Sets the current record's "middle_name" value
 * @method User                setGender()                Sets the current record's "gender" value
 * @method User                setBirthDate()             Sets the current record's "birth_date" value
 * @method User                setEmail()                 Sets the current record's "email" value
 * @method User                setPhone()                 Sets the current record's "phone" value
 * @method User                setSalt()                  Sets the current record's "salt" value
 * @method User                setPassword()              Sets the current record's "password" value
 * @method User                setPhoto()                 Sets the current record's "photo" value
 * @method User                setIsActive()              Sets the current record's "is_active" value
 * @method User                setIsSuperAdmin()          Sets the current record's "is_super_admin" value
 * @method User                setLastLogin()             Sets the current record's "last_login" value
 * @method User                setPasswordCheck()         Sets the current record's "password_check" value
 * @method User                setUserPermissions()       Sets the current record's "UserPermissions" collection
 * @method User                setUserGroup()             Sets the current record's "UserGroup" collection
 * @method User                setUserLog()               Sets the current record's "UserLog" collection
 * @method User                setSession()               Sets the current record's "Session" collection
 * @method User                setAgreementComplete()     Sets the current record's "AgreementComplete" collection
 * @method User                setSpecialist()            Sets the current record's "Specialist" collection
 * @method User                setReview()                Sets the current record's "Review" collection
 * @method User                setComment()               Sets the current record's "Comment" collection
 * @method User                setQuestion()              Sets the current record's "Question" collection
 * @method User                setReceptionContract()     Sets the current record's "Reception_contract" collection
 * @method User                setAnswer()                Sets the current record's "Answer" collection
 * @method User                setAnalysis()              Sets the current record's "Analysis" collection
 * @method User                setFeedback()              Sets the current record's "Feedback" collection
 * @method User                setNotice()                Sets the current record's "Notice" collection
 * @method User                setAttachedFamilyUsers()   Sets the current record's "Attached_family_users" collection
 * @method User                setMessageError()          Sets the current record's "Message_error" collection
 * @method User                setToken()                 Sets the current record's "Token" collection
 * 
 * @package    sf
 * @subpackage model
 * @author     Atma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUser extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('username', 'string', 128, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => 128,
             ));
        $this->hasColumn('first_name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('second_name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('middle_name', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('gender', 'string', 1, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 1,
             ));
        $this->hasColumn('birth_date', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('email', 'string', 128, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 128,
             ));
        $this->hasColumn('phone', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => 255,
             ));
        $this->hasColumn('salt', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             ));
        $this->hasColumn('password', 'string', 128, array(
             'type' => 'string',
             'length' => 128,
             ));
        $this->hasColumn('photo', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 1,
             ));
        $this->hasColumn('is_super_admin', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('last_login', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('password_check', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));


        $this->index('name_idx', array(
             'fields' => 
             array(
              0 => 'username',
             ),
             'unique' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Permission as UserPermissions', array(
             'refClass' => 'UserPermissions',
             'local' => 'user_id',
             'foreign' => 'permission_id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('UserGroup', array(
             'refClass' => 'UserGroupUsers',
             'local' => 'user_id',
             'foreign' => 'user_group_id'));

        $this->hasMany('UserLog', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Session', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('AgreementComplete', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Specialist', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Review', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Comment', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Question', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Reception_contract', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Answer', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Analysis', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Feedback', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Notice', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Attached_family_users', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Message_error', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Token', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}