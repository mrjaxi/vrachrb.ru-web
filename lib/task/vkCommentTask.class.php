<?php

class vkCommentTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'www'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev')
    ));

    $this->namespace        = 'vk';
    $this->name             = 'comment';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [vk:comment|INFO] task does things.
Call it with:

  [php symfony vk:comment|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);

    sfProjectConfiguration::getApplicationConfiguration('www', 'dev', true);
    
    sfContext::createInstance($this->configuration);
    
    $mailer = $this->getMailer();
    
    $map = Specialty::fullMap();

    foreach ($map as $m_key => $m)
    {
      $topic_id = $m;

      echo $topic_id . "\n";

      $method = 'https://api.vk.com/method/board.getComments';
      $param = array(
        'group_id' => sfConfig::get('app_vk_group_id'),
        'topic_id' => $topic_id,
        'count' => 100,
        'extended' => 1,
        'sort' => 'desc',
        'access_token' => sfConfig::get('app_vk_access_token_user')
      );

      $comments_json = ProjectUtils::post($method, $param);
      $comments_arr = json_decode($comments_json, true);
      if(is_array($comments_arr['response']['comments']))
      {
        foreach ($comments_arr['response']['comments'] as $comment)
        {
          if(is_array($comment['attachments']) && count($comment['attachments']) > 1)
          {
            $question_check = Doctrine::getTable("Question")->findOneByCommentId($comment['id']);
            $answer_check = Doctrine::getTable("Answer")->findOneByCommentId($comment['id']);
            if(!$question_check && !$answer_check)
            {
              Question::vkQuestionCheck($comment, $topic_id);

              echo "\n ok \n";

            }
          }
        }
      }
      sleep(2);
    }
  }
}
