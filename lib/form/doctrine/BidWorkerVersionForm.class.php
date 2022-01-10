<?php

/**
 * BidWorkerVersion form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BidWorkerVersionForm extends BaseBidWorkerVersionForm
{
  public function configure()
  {
    $this->useFields(array('bid_id', 'worker_id', 'training_center_branche_id', 'date_start', 'date_end', 'date_exam', 'version'));
  }
}
