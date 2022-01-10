<?php


class Receive_datetimeTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Receive_datetime');
    }
}