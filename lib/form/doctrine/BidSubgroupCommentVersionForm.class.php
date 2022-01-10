<?php

/**
 * BidSubgroupCommentVersion form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BidSubgroupCommentVersionForm extends BaseBidSubgroupCommentVersionForm
{
  public function configure()
  {
    $this->useFields(array('bid_id', 'subgroup', 'comment', 'version'));
  }
}
