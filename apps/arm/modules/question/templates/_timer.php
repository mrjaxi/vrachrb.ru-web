<?php
if($question->getClosedBy() != '' || $question->getApproved() == 1)
{
  $result = '<img alt="Checked" title="Checked" src="/sfDoctrinePlugin/images/tick.png">';
}
else
{
  $answers = $question->getAnswer();
  $count = count($answers);
  $last_time = strtotime($question->getCreatedAt());
  if($count > 0)
  {
    $specialist = $answers[($count - 1)]->getUser()->getSpecialist();
    if($specialist[0] != '')
    {
      $result = '<img alt="Checked" title="Checked" src="/sfDoctrinePlugin/images/tick.png">';
    }
    else
    {
      for($i = ($count - 1); $i >= 0; $i--)
      {
        $sp = $answers[$i]->getUser()->getSpecialist();
        if($sp[0] == '')
        {
          $last_time = strtotime($answers[$i]->getCreatedAt());
          $no_answer = true;
          break;
        }
      }
    }
  }
  else
  {
    $no_answer = true;
  }
}
if($no_answer)
{
  $result = 'Без ответа';
  $time = time() - $last_time;
  if($time > 60)
  {
    $min = floor($time / 60);
    if($min > 60)
    {
      $hour = floor($min / 60);
    }
  }
  $result .= '<br>' . ($hour ? $hour . 'ч ' : '') . ($min - ($hour * 60)) . 'мин';
}
echo '<div class="' . ($hour ? 'question_no_answer' : '') . '">' . $result . '</div>';