<?php


class Specialist_work_placeTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Specialist_work_place');
    }
}