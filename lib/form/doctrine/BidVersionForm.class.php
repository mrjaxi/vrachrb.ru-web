<?php

/**
 * BidVersion form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BidVersionForm extends BaseBidVersionForm
{
  public function configure()
  {
    $this->useFields(array('version_id', 'user_id', 'course_id', 'date_start', 'date_end', 'state_id', 'created_at', 'version'));
  }
}
