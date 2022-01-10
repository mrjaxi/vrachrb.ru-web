<?php

/**
 * BaseSheetHistory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * @property Doctrine_Collection $Specialtys
 * @property Doctrine_Collection $SheetHistorySpecialty
 * @property Doctrine_Collection $SheetHistoryField
 * 
 * @method string              getTitle()                 Returns the current record's "title" value
 * @method Doctrine_Collection getSpecialtys()            Returns the current record's "Specialtys" collection
 * @method Doctrine_Collection getSheetHistorySpecialty() Returns the current record's "SheetHistorySpecialty" collection
 * @method Doctrine_Collection getSheetHistoryField()     Returns the current record's "SheetHistoryField" collection
 * @method SheetHistory        setTitle()                 Sets the current record's "title" value
 * @method SheetHistory        setSpecialtys()            Sets the current record's "Specialtys" collection
 * @method SheetHistory        setSheetHistorySpecialty() Sets the current record's "SheetHistorySpecialty" collection
 * @method SheetHistory        setSheetHistoryField()     Sets the current record's "SheetHistoryField" collection
 * 
 * @package    sf
 * @subpackage model
 * @author     Atma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSheetHistory extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('sheet_history');
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Specialty as Specialtys', array(
             'refClass' => 'SheetHistorySpecialty',
             'local' => 'sheet_history_id',
             'foreign' => 'specialty_id',
             'onDelete' => 'CASCADE'));

        $this->hasMany('SheetHistorySpecialty', array(
             'local' => 'id',
             'foreign' => 'sheet_history_id'));

        $this->hasMany('SheetHistoryField', array(
             'local' => 'id',
             'foreign' => 'sheet_history_id'));
    }
}