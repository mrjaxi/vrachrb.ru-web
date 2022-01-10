<?php


class Specialist_onlineTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Specialist_online');
    }
}