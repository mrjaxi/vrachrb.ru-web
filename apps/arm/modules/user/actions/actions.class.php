<?php

require_once dirname(__FILE__).'/../lib/userGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/userGeneratorHelper.class.php';

/**
 * user actions.
 *
 * @package    sf
 * @subpackage user
 * @author     Atma
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends autoUserActions
{
  public function executeIndex(sfWebRequest $request)
  {
    
    return parent::executeIndex($request);
  }
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $notice = $form->getObject()->isNew() ? 'The item was created successfully.' : 'The item was updated successfully.';
      try
      {
        $user = $form->save();
        $post = $request->getParameter($form->getName());
        if($user->getEmail())
        {
          $old = $user->getModified(false, true);
          $new = $user->getModified(true, true);
          
          
          if($user->getIsSuperAdmin() && count(array_diff($old, $new)) > 0)
          {
            $mailer = $this->getMailer();
            $message = $mailer->compose(
              sfConfig::get('app_email_sender', 'devnull@atmadev.ru'), 
              array($user->getEmail()), 
              'Учетная запись к «АРМ предприятия»'
            );
            $message->setContentType('text/plain; charset=UTF-8');
            $message->setBody($this->getPartial('user/mail_create', array('user'=> $user, 'password' => $post['password'])));
            $result = $mailer->send($message);
          }
          
        }

        $values = $request->hasParameter('user_permissions') && is_array($request->getParameter('user_permissions')) ? $request->getParameter('user_permissions') : array();
        $existing = ProjectUlils::arrayKeysFilter($user->getUserPermissions(), 'id');
        
        $unlink = array_diff($existing, $values);

        if (count($unlink) > 0)
        {
          $q = Doctrine_Query::create()
            ->delete("r.*")
            ->from("UserPermissions r")
            ->whereIn("r.permission_id", array_values($unlink))
            ->andWhere("r.user_id= ?", $user->getId())
            ->execute();
        }
        $link = array_diff($values, $existing);
        
        if (count($link) > 0)
        {
          foreach($link as $id)
          {
            $user_permissions = new UserPermissions();
            $user_permissions->setPermissionId($id);
            $user_permissions->setUserId($user->getId());
            $user_permissions->save();
          }
        }
        
//        $q = Doctrine_Query::create()
//          ->delete("r.*")
//          ->from("UserDivision r")
//          ->andWhere("r.user_id= ?", $user->getId())
//          ->execute();
        
//        if($request->getParameter('user_division') != '')
//        {
//          $ud = new UserDivision();
//          $ud->setDivisionId($request->getParameter('user_division'));
//          $ud->setUserId($user->getId());
//          $ud->save();
//        }

//        $ruserdatas = $request->hasParameter('userdata') && is_array($request->getParameter('userdata')) ? $request->getParameter('userdata') : array();
//        foreach($ruserdatas as $ruserdata_key => $ruserdata)
//        {
//          $userdata = Doctrine::getTable('Userdata')->findOneByUserIdAndDataType($user->getId(), $ruserdata_key);
//          if(!$userdata)
//          {
//            $userdata = new Userdata();
//            $userdata->setUserId($user->getId());
//            $userdata->setDataType($ruserdata_key);
//          }
//          $userdata->setDataValue($ruserdata);
//          $userdata->save();
//        }
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
      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $user)));
      if ($request->hasParameter('_save_and_add'))
      {
        $this->getUser()->setFlash('notice', $notice.' You can add another one below.');
        $this->redirect('@user_new');
      }
      elseif ($request->hasParameter('_save_and_list'))
      {
        $this->redirect('@user');
      }
      else
      {
        if(!$request->isXmlHttpRequest())
        {
          $this->getUser()->setFlash('notice', $notice);
          $this->redirect(array('sf_route' => 'user_edit', 'sf_subject' => $user));
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
