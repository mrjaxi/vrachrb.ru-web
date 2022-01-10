<?php

/**
 * Seo form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SeoForm extends BaseSeoForm
{
  public function configure()
  {
    $this->useFields(array('title_h', 'title_tag', 'description_tag', 'url'));

    $this->widgetSchema['title_h'] = new sfWidgetFormTextarea(array(), array('required' => false, 'cols' => 61, 'rows' => 2));
    $this->widgetSchema['title_tag'] = new sfWidgetFormTextarea(array(), array('required' => false, 'cols' => 61, 'rows' => 2));
    $this->widgetSchema['description_tag'] = new sfWidgetFormTextarea(array(), array('required' => false, 'cols' => 61, 'rows' => 2));
    $this->widgetSchema['url'] = new sfWidgetFormInputText(array(), array('required' => true, 'size' => 63));
  }
}
