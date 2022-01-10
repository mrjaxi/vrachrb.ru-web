<?php

/**
 * UserGroupUsers form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserGroupUsersForm extends BaseUserGroupUsersForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'user_group_id'));
  }
}
