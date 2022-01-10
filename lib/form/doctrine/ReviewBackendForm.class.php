<?php

/**
 * Review form.
 *
 * @package    sf
 * @subpackage form
 * @author     SyLord
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ReviewBackendForm extends BaseReviewForm
{
  public function configure()
  {
    $this->useFields(array('from_name', 'body', 'is_activated'));
    
    $this->widgetSchema['from_name'] = new sfWidgetFormInputText(array(), array('size' => '70'));
    //$this->widgetSchema['email'] = new sfWidgetFormInputText(array(), array('size' => '50'));
    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('rows' => '10', 'cols' => '100'));

  }
}
