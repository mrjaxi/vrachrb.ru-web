<?php
class armConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
    $this->dispatcher->connect('admin.save_object', array('UserLog', 'saveObject'));
    $this->dispatcher->connect('admin.delete_object', array('UserLog', 'deleteObject'));
    
    
    
  }
}
