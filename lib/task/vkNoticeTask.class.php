<?php

class vknoticeTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'www'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),    
      new sfCommandOption('answer_id', null, sfCommandOption::PARAMETER_REQUIRED, 'The answer id', 'www'),    
    ));

    $this->namespace        = 'vk';
    $this->name             = 'notice';
  }

  protected function execute($arguments = array(), $options = array())
  {
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $a_id = $options['answer_id'];

    if($a_id && is_numeric($a_id))
    {
      $answer = Doctrine::getTable("Answer")->find($a_id);
      if($answer)
      {
        $question = $answer->getQuestion();
        if($question)
        {
          if($question->getCommentId() != '' && $question->getUserId() != $answer->getUserId())
          {
            $vk_message_type = $question->getTopicId() ? 'topic' : false;
            if(!Answer::AnswerType($answer->getType(), false) || $answer->getType() == 'please_analysis')
            {
              if(sfConfig::get('app_vk_publish') && !$answer->getCommentId() && ($question->getVkNotice() == 0 || !$question->getVkNotice()))
              {              
                $comment_id = Question::syncToVk($answer, $vk_message_type);
                if($comment_id)
                {
                  $question->setVkNotice(1);
                  $question->save();

                  $answer->setCommentId($comment_id);
                  $answer->save();
                }                
              }
            }
          }
        } 
      }    
    }
  }
}