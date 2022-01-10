<?php

/**
 * Question_black_list form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class Question_black_listForm extends BaseQuestion_black_listForm
{
  public function configure()
  {
    $this->useFields(array('question_id', 'specialist_id', 'created_at'));
  }
}
