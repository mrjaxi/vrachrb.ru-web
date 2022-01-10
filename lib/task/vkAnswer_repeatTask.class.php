<?php

class vkAnswer_repeatTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'www'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
    ));

    $this->namespace        = 'vk';
    $this->name             = 'answer_repeat';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [vk:answer_repeat|INFO] task does things.
Call it with:

  [php symfony vk:answer_repeat|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $file = json_decode(file_get_contents(sfConfig::get('sf_log_dir') . '/vk_answers_log.txt'), true);
    $file_change = $file;

    if(count($file) > 0)
    {
      foreach ($file as $f_key => $f)
      {
        $answers = Doctrine::getTable("Answer")->findByQuestionId($f['question_id']);
        foreach ($answers as $a)
        {
          if($a->getBody() == $f['body'])
          {
            $check_comment_id = Question::syncToVk($a);
            if($check_comment_id)
            {
              unset($file_change[$f_key]);
            }
          }
        }
      }
      file_put_contents(sfConfig::get('sf_log_dir') . '/vk_answers_log.txt', json_encode($file_change), LOCK_EX);
    }
  }
}
