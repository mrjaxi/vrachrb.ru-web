<?php


class Consilium_answerTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Consilium_answer');
    }
}