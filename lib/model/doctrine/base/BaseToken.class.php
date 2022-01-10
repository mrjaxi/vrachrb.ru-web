<?php

/**
 * BaseToken
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $value
 * @property integer $user_id
 * @property User $User
 * 
 * @method string  getValue()   Returns the current record's "value" value
 * @method integer getUserId()  Returns the current record's "user_id" value
 * @method User    getUser()    Returns the current record's "User" value
 * @method Token   setValue()   Sets the current record's "value" value
 * @method Token   setUserId()  Sets the current record's "user_id" value
 * @method Token   setUser()    Sets the current record's "User" value
 * 
 * @package    sf
 * @subpackage model
 * @author     Atma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseToken extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('token');
        $this->hasColumn('value', 'string', 1000, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 1000,
             ));
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}