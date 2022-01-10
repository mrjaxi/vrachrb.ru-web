<?php

/**
 * Analysis_type form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Analysis_typeForm extends BaseAnalysis_typeForm
{
  public function configure()
  {
    $this->useFields(array('title', 'description'));
  }
}
