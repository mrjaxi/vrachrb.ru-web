<?php

/**
 * BaseAgreementComplete
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $user_id
 * @property integer $agreement_id
 * @property User $User
 * @property Agreement $Agreement
 * 
 * @method integer           getUserId()       Returns the current record's "user_id" value
 * @method integer           getAgreementId()  Returns the current record's "agreement_id" value
 * @method User              getUser()         Returns the current record's "User" value
 * @method Agreement         getAgreement()    Returns the current record's "Agreement" value
 * @method AgreementComplete setUserId()       Sets the current record's "user_id" value
 * @method AgreementComplete setAgreementId()  Sets the current record's "agreement_id" value
 * @method AgreementComplete setUser()         Sets the current record's "User" value
 * @method AgreementComplete setAgreement()    Sets the current record's "Agreement" value
 * 
 * @package    sf
 * @subpackage model
 * @author     Atma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseAgreementComplete extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('agreement_complete');
        $this->hasColumn('user_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('agreement_id', 'integer', null, array(
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

        $this->hasOne('Agreement', array(
             'local' => 'agreement_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}