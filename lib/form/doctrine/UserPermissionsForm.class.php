<?php

/**
 * UserPermissions form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UserPermissionsForm extends BaseUserPermissionsForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'permission_id'));
  }
}
