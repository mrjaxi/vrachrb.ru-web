<?php

/**
 * QuestionSheetHistory form.
 *
 * @package    sf
 * @subpackage form
 * @author     Atma
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class QuestionSheetHistoryForm extends BaseQuestionSheetHistoryForm
{
  public function configure()
  {
    $this->useFields(array('question_id', 'sheet_history_field_id', 'value'));
  }
}
