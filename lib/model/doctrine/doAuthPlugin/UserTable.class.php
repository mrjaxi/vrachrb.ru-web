<?php


class UserTable extends PluginUserTable
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('User');
    }
}