<?php


class Reception_contractTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Reception_contract');
    }
}