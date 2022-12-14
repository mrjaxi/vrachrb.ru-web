<?php

/**
 * BasePage
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * @property string $folder
 * @property text $body
 * @property boolean $is_activated
 * 
 * @method string  getTitle()        Returns the current record's "title" value
 * @method string  getFolder()       Returns the current record's "folder" value
 * @method text    getBody()         Returns the current record's "body" value
 * @method boolean getIsActivated()  Returns the current record's "is_activated" value
 * @method Page    setTitle()        Sets the current record's "title" value
 * @method Page    setFolder()       Sets the current record's "folder" value
 * @method Page    setBody()         Sets the current record's "body" value
 * @method Page    setIsActivated()  Sets the current record's "is_activated" value
 * 
 * @package    sf
 * @subpackage model
 * @author     Atma
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePage extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('page');
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('folder', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'unique' => true,
             'length' => 255,
             ));
        $this->hasColumn('body', 'text', null, array(
             'type' => 'text',
             'notnull' => true,
             ));
        $this->hasColumn('is_activated', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => true,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}