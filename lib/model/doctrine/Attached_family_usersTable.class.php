<?php


class Attached_family_usersTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Attached_family_users');
    }
}