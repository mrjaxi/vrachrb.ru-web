<?php

abstract class PluginUser extends BaseUser
{
  protected
    $_groups         = null,
    $_permissions    = null,
    $_allPermissions = null;
  public function __toString()
  {
    return (string) $this->getUsername();
  }

  public function setPassword($password)
  {
    if (!$password && 0 == strlen($password))
    {
      return;
    }
    if(strlen($password) < 32)
    {
      if (!$salt = $this->getSalt())
      {
        $salt = sha1(uniqid(mt_rand(), true));
        $this->setSalt($salt);
      }
      $modified = $this->getModified();
      $algorithm = sfConfig::get('app_doAuth_algorithm_callable', 'sha1');
      $algorithmAsStr = is_array($algorithm) ? $algorithm[0].'::'.$algorithm[1] : $algorithm;
      if (!is_callable($algorithm))
      {
        throw new sfException(sprintf('The algorithm callable "%s" is not callable.', $algorithmAsStr));
      }
      parent::_set('password', call_user_func_array($algorithm, array($salt.$password)));
    }
    else
    {
      parent::_set('password', $password);
    }
  }
  public function getFIorUsername()
  {
    $names = array();
    if($this->getSecondName())
    {
      $names[] = $this->getSecondName();
    }
    if($this->getFirstName())
    {
      $names[] = $this->getFirstName();
    }
    return count($names) > 0 ? implode(' ', $names) : $this->getUsername();
  }
}
