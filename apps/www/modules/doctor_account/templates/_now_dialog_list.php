<?php

$questions = $sf_user->getAccount()->getOpenQuestions('question_list', false, $filter_da_list);
if(count($questions) > 0)
{
	echo '<div class="white_box pc_user_page" style="padding-top: 0;">';
	foreach ($questions as $element)
	{
		$close_text = $element['closed_by'] && $element['closed_by'] != 0 ? 'close_question' : '';
		if(count($element['Review']) > 0)
		{
			if(time() - strtotime($element['closing_date']) < 86400)
			{
				include_partial('c_item', array('questions' => $questions, 'element' => $element, 'type' => 'list', 'location' => 'now_dialog', 'close_text' => $close_text));
			}
		}
		else
		{
			$message = false;
			if(!$element['closed_by'] || $element['closed_by'] == '')
			{
				$text = 'Ожидает вашего ответа';
				$color = 'blue';
				if(count($element['Answer']) > 0 && $element['Answer'][0]['user_id'] != $element['user_id'])
				{
					$text = 'Вы ответили';
					$color = 'green';
				}
				$message = '<span class="pc_chat__item__name_answered pc_chat__s_' . $color . '">' . $text . '</span>';
			}

			include_partial('c_item', array('name_answered' => $message, 'questions' => $questions, 'element' => $element, 'type' => 'list', 'location' => 'now_dialog', 'close_text' => $close_text));
		}
	}
	echo '</div>';
}
else
{
	?>
	<div class="pc_not_dialog">
		<div class="pc_not_dialog__inner">
			Нет текущих бесед
		</div>
	</div>
	<?php
}
?>