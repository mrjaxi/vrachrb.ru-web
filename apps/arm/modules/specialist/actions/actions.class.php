<?php

require_once dirname(__FILE__).'/../lib/specialistGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/specialistGeneratorHelper.class.php';

/**
 * specialist actions.
 *
 * @package    sf
 * @subpackage specialist
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class specialistActions extends autoSpecialistActions
{
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $request_form = $request->getParameter($form->getName());
    $new_password = $request_form['user']['new_password'];
    unset($request_form['user']['new_password']);
    $form->bind($request_form);
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
      try
      {
        $specialist = $form->save();

        if (trim($new_password) != '')
        {
          $user = $specialist->getUser();
          $user->setPassword($new_password);
          $user->save();
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
      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $specialist)));
      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('notice', $notice.' You can add another one below.');
        $this->redirect('@specialist_new');
      }
      elseif ($request->hasParameter('_save_and_list'))
      {
        $this->redirect('@specialist');
      }
      else
      {
        if(!$request->isXmlHttpRequest())
        {
          $this->getUser()->setFlash('notice', $notice);
          $this->redirect($request->hasParameter('return') ? $request->getParameter('return') : array('sf_route' => 'specialist_edit', 'sf_subject' => $specialist));
        }
      }
    }
    else
    {
      $this->getUser()->setFlash('error', 'The item has not been saved due to some errors.', false);
      $this->getResponse()->addHttpMeta('Sf-Form-Error', '1', true);
    }
  }
  protected function buildQuery()
  {
    $tableMethod = $this->configuration->getTableMethod();
    $query = Doctrine::getTable('Specialist')
      ->createQuery('r');

    if ($tableMethod)
    {
      $query = Doctrine::getTable('Specialist')->$tableMethod($query);
    }

    $query->leftJoin('r.User u');
    $query->leftJoin('r.Specialty s');

    $this->addSortQuery($query);

    $event = $this->dispatcher->filter(new sfEvent($this, 'admin.build_query'), $query);


    $this->addSearchQuery($query);



    $this->addCheckedQuery($query);

    $query = $event->getReturnValue();


    return $query;
  }
  protected function isValidSortColumn($column)
  {
    return true;
  }

}
