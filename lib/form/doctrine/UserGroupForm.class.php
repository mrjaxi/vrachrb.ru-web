<?php

/**
 * UserGroup form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserGroupForm extends BaseUserGroupForm
{
  public function configure()
  {
    $this->useFields(array('title', 'user_group_users_list'));
    
    $this->widgetSchema['user_group_users_list'] = new sfWidgetFormDoctrineChoice(array(
      'multiple' => true, 
      'model' => NULL, 
      'query' => Doctrine_Query::create()->select("u.*")->from("User u")->where("u.is_super_admin = 1 AND u.username != 'root'")->orderBy("u.username"), 
      'expanded' => true)
    );
  }
}
