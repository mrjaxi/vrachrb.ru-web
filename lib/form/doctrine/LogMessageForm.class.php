<?php

/**
 * LogMessage form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class LogMessageForm extends BaseLogMessageForm
{
  public function configure()
  {
    $this->useFields(array('type', 'body', 'created_at'));
  }
}
