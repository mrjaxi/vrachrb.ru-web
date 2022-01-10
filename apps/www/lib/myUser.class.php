<?php
class myUser extends doAuthSecurityUser
{
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);
    if($this->isAuthenticated())
    {
      $sp_id = $this->getAccount()->getSpecialist();
      $this->addCredential($sp_id[0]['id'] ? 'doctor' : 'user');

      if($sp_id[0]['id'])
      {
        $sp_online = Doctrine::getTable('Specialist_online')->findOneBySpecialistId($sp_id[0]['id']);

        if(!$sp_online)
        {
          $sp_online = new Specialist_online();
          $sp_online->setSpecialistId($sp_id[0]['id']);
        }
        $sp_online->setDate(date('Y-m-d' . ' ' . 'H:i:s'));
        $sp_online->save();
      }
    }
  }
}
