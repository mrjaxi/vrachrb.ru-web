<?php

/**
 * Page form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PageForm extends BasePageForm
{
  public function configure()
  {
    $this->useFields(array('title', 'folder', 'body', 'is_activated', 'created_at'));

    $this->widgetSchema['body'] = new sfWidgetFormTextarea(array(), array('required' => true, 'cols' => 61, 'rows' => 20, 'class' => 'rich'));
  }
}
