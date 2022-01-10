<?php


class Question_black_listTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Question_black_list');
    }
}