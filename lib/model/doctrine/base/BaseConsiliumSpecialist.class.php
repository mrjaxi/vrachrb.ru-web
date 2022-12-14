<?php

/**
 * BaseConsiliumSpecialist
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $consilium_id
 * @property integer $specialist_id
 * @property Consilium $Consilium
 * @property Specialist $Specialist
 * 
 * @method integer             getConsiliumId()   Returns the current record's "consilium_id" value
 * @method integer             getSpecialistId()  Returns the current record's "specialist_id" value
 * @method Consilium           getConsilium()     Returns the current record's "Consilium" value
 * @method Specialist          getSpecialist()    Returns the current record's "Specialist" value
 * @method ConsiliumSpecialist setConsiliumId()   Sets the current record's "consilium_id" value
 * @method ConsiliumSpecialist setSpecialistId()  Sets the current record's "specialist_id" value
 * @method ConsiliumSpecialist setConsilium()     Sets the current record's "Consilium" value
 * @method ConsiliumSpecialist setSpecialist()    Sets the current record's "Specialist" value
 * 
 * @package    sf
 * @subpackage model
 * @author     Atma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseConsiliumSpecialist extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('consilium_specialist');
        $this->hasColumn('consilium_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
        $this->hasColumn('specialist_id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Consilium', array(
             'local' => 'consilium_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Specialist', array(
             'local' => 'specialist_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}