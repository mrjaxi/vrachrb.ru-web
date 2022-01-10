<?php

class noticeSentTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'www'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'notice';
    $this->name             = 'sent';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [notice:sent|INFO] task does things.
Call it with:

  [php symfony notice:sent|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    /*
    $method = 'https://api.vk.com/method/board.addComment';
    $param = array(
      'group_id' => sfConfig::get('app_vk_group_id'),
      'topic_id' => 32587801,
      'text' => 'Service_message',
      'from_group' => 1,     
      'access_token' => '2f96ae8d1a39830d55ac70ab1a6ce9bbe48f0ae312de02fc90428d8be911b9ab2a945252d723dbdb717e0'
    );
    */
     /*
    $method = 'https://oauth.vk.com/access_token';
    $param = array(
      'client_id' => '5367670',
      'client_secret' => 'jryleKXkh3BNYQBqfhpH',
      'redirect_uri' => 'http://vrachrb.ru',
      'code' => 'f0ef3c6aee384d152e'           
    );
    

    $r = ProjectUtils::post($method, $params);
    
    echo $r;
    */
    
    
    $r = file_get_contents('https://oauth.vk.com/access_token?client_id=5367670&client_secret=jryleKXkh3BNYQBqfhpH&redirect_uri=http://vrachrb.ru&code=f0ef3c6aee384d152e');

    echo $r . "\n";
    
    file_put_contents(sfConfig::get('sf_log_dir') . '/vk_test.txt', $r . "\n" . print_r($r) . "\n" . '__');
    
    /*   
    echo Notice::noticeSent(3) . "\n";
    
    
    

    

    print_r($user_info);
    echo "\n";
    echo $user_info;
    echo "\n";
    print_r($user_info, true);    
    echo "\n ok \n";
    $a = file_get_contents('https://oauth.vk.com/access_token?client_id=5367670&client_secret=jryleKXkh3BNYQBqfhpH&redirect_uri=http://vrachrb.ru&code=582916ec8f36dc9c7e');
    file_put_contents(sfConfig::get('sf_log_dir') . '/vk_test.txt', $a . '__');
    echo $a;
    */
    
            
  }
}
