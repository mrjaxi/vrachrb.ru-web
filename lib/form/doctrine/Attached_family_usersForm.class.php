<?php

/**
 * Attached_family_users form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Attached_family_usersForm extends BaseAttached_family_usersForm
{
  public function configure()
  {
    $this->useFields(array('user_id', 'user_about_id'));
  }
}
