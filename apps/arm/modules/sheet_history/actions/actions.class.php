<?php

require_once dirname(__FILE__).'/../lib/sheet_historyGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sheet_historyGeneratorHelper.class.php';

/**
 * sheet_history actions.
 *
 * @package    sf
 * @subpackage sheet_history
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sheet_historyActions extends autoSheet_historyActions
{
  public function executeGet_template(sfWebRequest $request)
  {
    $this->field = new SheetHistoryField();
  }
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
      try
      {
        $sheet_history = $form->save();
        if($request->hasParameter('sheet_history__fields'))
        {
          $fields_exist = array();
          $fields_ids = array();
          foreach($sheet_history->getSheetHistoryField() as $field)
          {
            $fields_exist[$field->getId()] = $field;
          }
          foreach(json_decode($request->getParameter('sheet_history__fields'), true) as $order_field => $field)
          {
            if(isset($fields_exist[$field['id']]))
            {
              $sheet_history_field = $fields_exist[$field['id']];
            }
            else
            {
              $sheet_history_field = new SheetHistoryField();
              $sheet_history_field->setSheetHistoryId($sheet_history->getId());
            }
            $sheet_history_field->setTitle($field['title']);
            $sheet_history_field->setFieldType($field['type']);
            $sheet_history_field->setFieldOptions(serialize($field['options']));
            $sheet_history_field->setOrderField($order_field);
            $sheet_history_field->setIsRequired($field['is_required']);
            $sheet_history_field->save();
            $fields_ids[] = $sheet_history_field->getId();
          }
          foreach(array_diff(array_keys($fields_exist), $fields_ids) as $id)
          {
            $fields_exist[$id]->delete();
          }
        }
      } 
      catch (Doctrine_Validator_Exception $e)
      {
        $errorStack = $form->getObject()->getErrorStack();
        $message = get_class($form->getObject()) . ' has ' . count($errorStack) . " field" . (count($errorStack) > 1 ?  's' : null) . " with validation errors: ";
        foreach ($errorStack as $field => $errors)
        {
          $message .= "$field (" . implode(", ", $errors) . "), ";
        }
        $message = trim($message, ', ');
        $this->getUser()->setFlash('error', $message);
        return sfView::SUCCESS;
      }
      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $sheet_history)));
      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('notice', $notice.' You can add another one below.');
        $this->redirect('@sheet_history_new');
      }
      elseif ($request->hasParameter('_save_and_list'))
      {
        $this->redirect('@sheet_history');
      }
      else
      {
        if(!$request->isXmlHttpRequest())
        {
          $this->getUser()->setFlash('notice', $notice);
          $this->redirect($request->hasParameter('return') ? $request->getParameter('return') : array('sf_route' => 'sheet_history_edit', 'sf_subject' => $sheet_history));
        }
      }
    }
    else
    {
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
      $this->getResponse()->addHttpMeta('Sf-Form-Error', '1', true);
    }
  }
  
  
}
