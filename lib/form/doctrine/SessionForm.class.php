<?php

/**
 * Session form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SessionForm extends BaseSessionForm
{
  public function configure()
  {
    $this->useFields(array('session_data', 'session_time', 'user_id', 'created_at'));
  }
}
