<?php

class vknoticetestTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'www'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = '';
    $this->name             = 'vknoticetest';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [vknoticetest|INFO] task does things.
Call it with:

  [php symfony vknoticetest|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();











    $elements = Doctrine_Query::create()
    	->select("e.*")
    	->from("Prompt e")
    	->execute()
    ;
    foreach ($elements as $key => $element) {

			$element->save();    	

    	echo $element->getId() . "\n";
    }














//     $method = 'https://api.vk.com/method/board.addComment';

//     $param = array(
//       'group_id' => sfConfig::get('app_vk_group_id'),
//       'topic_id' => 33433000,
//       'text' => 'сервисное сообщение',
//       'from_group' => 1,
// //      'guid' => $guid,
//       'access_token' => sfConfig::get('app_vk_access_token_user')
//     );

//     $json = json_decode(ProjectUtils::post($method, $param), true);

//     file_put_contents(sfConfig::get('sf_log_dir') . '/bro.txt', print_r($json, true));


//     echo 'ok';



  }
}
