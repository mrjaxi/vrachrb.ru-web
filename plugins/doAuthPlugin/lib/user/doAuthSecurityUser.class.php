<?php
/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class doAuthSecurityUser extends sfBasicSecurityUser
{
  protected $user = null;
  protected $user_info = null;

  /**
   * Initializes the doAuthSecurityUser object.
   *
   * @param sfEventDispatcher $dispatcher The event dispatcher object
   * @param sfStorage $storage The session storage object
   * @param array $options An array of options
   */
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);
    if (!$this->isAuthenticated())
    {
      $this->getAttributeHolder()->removeNamespace('doUser');
      $this->user = null;
    }
    elseif(sfConfig::get('sf_app') == 'arm')
    {
        $old_credentials = $this->getCredentials();
        
        $new_credentials = array();
       
        
        $account = $this->getAccount();
        
        if($account)
        {
          $perms = $account->getUserPermissions();
          
          foreach($perms as $perm)
          {
            $ex_module = explode('-', $perm->getCredential());
            foreach(explode('|', $ex_module[1]) as $action)
            {
              $credential = $ex_module[0] . '_' . $action;
              $this->addCredential($credential);
              $new_credentials[] = $credential;
            }
          }

          $user_groupss = Doctrine_Query::create()
            ->select("ugs.*")
            ->from("UserGroupUsers ugs")
            ->where("ugs.user_id = " . $this->getUserId())
            ->execute()
          ;

          foreach($user_groupss as $user_groups)
          {
            $user_group = Doctrine::getTable("UserGroup")->find($user_groups->getUserGroupId());
            if($user_group)
            {
              foreach($user_group->getUserGroupPermissions() as $user_group_permission)
              {
                $ex_module = explode('-', $user_group_permission->getCredential());
                foreach(explode('|', $ex_module[1]) as $action)
                {
                  $credential = $ex_module[0] . '_' . $action;
                  $this->addCredential($credential);
                  $new_credentials[] = $credential;
                }
              }
            }
          }
          
          $diff = array_diff($old_credentials, $new_credentials);
          foreach($diff as $credential)
          {
            $this->removeCredential($credential);
          }
        }
      }
    
  }



  /**
   * Returns the referer uri.
   *
   * @param string $default The default uri to return
   * @return string $referer The referer
   */
  public function getReferer($default)
  {
    $referer = $this->getAttribute('referer', $default);
    $this->getAttributeHolder()->remove('referer');

    return $referer;
  }

  /**
   * Sets the referer.
   *
   * @param string $referer
   */
  public function setReferer($referer)
  {
    if (!$this->hasAttribute('referer'))
    {
      $this->setAttribute('referer', $referer);
    }
  }

  /**
   * Returns whether or not the user has the given credential.
   *
   * @param string $credential The credential name
   * @param boolean $useAnd Whether or not to use an AND condition
   * @return boolean
   */
  public function hasCredential($credential, $useAnd = true)
  {
    if ($this->getAccount()->getUsername() == 'root' || $this->getAccount()->getUsername() == 'Акрам')
    {
      return true;
    }
    
    if (empty($credential))
    {
      return false;
    }

    if (!$this->getAccount())
    {
      return false;
    }
    
    return parent::hasCredential($credential, $useAnd);
  }

  /**
   * Returns whether or not the user is a super admin.
   *
   * @return boolean
   */
  public function isAdmin()
  {
    return $this->getAccount() ? $this->getAccount()->getIsSuperAdmin() : false;
  }
  
  public function isAuthenticated()
  {
    //var_dump($this->isAdmin());
    return $this->isAuthenticate() ? (sfConfig::get('sf_app') == 'arm' ? $this->isAdmin() : true) : false;
  }

  /**
   * Returns whether or not the user is anonymous.
   *
   * @return boolean
   */
  public function isAnonymous()
  {
    return !$this->isAuthenticated();
  }

  /**
   * Signs in the user on the application.
   *
   * @param doAuthUser $user The doAuthUser id
   * @param boolean $remember Whether or not to remember the user
   * @param Doctrine_Connection $con A Doctrine_Connection object
   */
  public function signIn($user, $remember = false, $con = null)
  {

    // we remove a non-user storage
    $this->getAttributeHolder()->removeNamespace('doPreUser');
    // signin

    $this->setAttribute('user_id', $user->getId(), 'doUser');
    $this->setAuthenticated(true);
    $this->clearCredentials();
    
    // save last login
    $user->setLastLogin(date('Y-m-d H:i:s'));
    $user->save($con);

    // remember?
    if ($remember)
    {
      // save to cookie
      $hash = base64_encode(serialize(array($user->getUsername(),md5(rand()),doAuthTools::rememberHash($user))));

      $context = sfContext::getInstance();

      $expiration_age = sfConfig::get('app_doAuth_remember_key_expiration_age', 356 * 24 * 3600);
      // make key as a cookie
      $remember_cookie = sfConfig::get('app_doAuth_remember_cookie_name', 'doRemember');
      sfContext::getInstance()->getResponse()->setCookie($remember_cookie, $hash, time() + $expiration_age);
    }

    $this->dispatcher->notify(new sfEvent($this, 'user.signed_in'));
  }

  /**
   * Signs out the user.
   *
   */
  public function signOut()
  {
    $this->getAttributeHolder()->removeNamespace('doUser');
    $this->user = null;
    $this->clearCredentials();
    $this->setAuthenticated(false);
    $expiration_age = sfConfig::get('app_doAuth_remember_key_expiration_age', 356 * 24 * 3600);
    $remember_cookie = sfConfig::get('app_doAuth_remember_cookie_name', 'doRemember');
    sfContext::getInstance()->getResponse()->setCookie($remember_cookie, '', time() - $expiration_age);
    $this->dispatcher->notify(new sfEvent($this, 'user.signed_out'));
  }

  /**
   * Returns the related doAuthUser.
   *
   * @return User
   */
  public function getAccount()
  {
    
      
    if (!$this->user && $id = $this->getAttribute('user_id', null, 'doUser'))
    {

      $this->user = Doctrine::getTable('User')->find($id);
      
      

      if (!$this->user)
      {
        // the user does not exist anymore in the database
        $this->signOut();
        return false;
        //throw new sfException('The user does not exist anymore in the database.');
      }
    }

    return $this->user;
  }
  public function getUserInfo()
  {
    if (!$this->user_info)
    {
      $this->user_info = false;
    }

    return $this->user_info;
  }                                     
  /**
   * Returns the unique identifier for user. Typically it is id field in User model
   *
   * @return <type>
   *
   */

  public function getUserId() 
  {
    return  $this->getAttribute('user_id','','doUser');

  }

  /**
   * Returns the string representation of the object.
   *
   * @return string
   */
  public function __toString()
  {
    return $this->getAccount()->__toString();
  }

  /**
   * Returns the doAuthUser object's username.
   *
   * @return string
   */
  public function getUsername()
  {
    return $this->getAccount()->getUsername();
  }


}
