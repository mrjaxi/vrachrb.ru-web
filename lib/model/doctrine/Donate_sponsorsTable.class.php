<?php


class Donate_sponsorsTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Donate_sponsors');
    }
}