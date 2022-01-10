<?php

  $string_end = array(
    'Question' => 'щий вопрос',
    'Article' => 'щая статья',
    'Prompt' => 'щий совет',
    'News' => 'щая новость'
  );

  echo '<div class="similar_post similar_post_' . strtolower($module) . '" ' . ($style != '' ? 'style="' . $style . '"' : '') . '>';
  foreach ($elements as $element_key => $element)
  {
    if($element['id'] == $element_id)
    {
      for($i = ($element_key - 1); $i <= ($element_key + 1); $i ++)
      {
        if($i != $element_key)
        {
          $select_element = ($elements[$i] ? $elements[$i] : ($i < $element_key ? $elements[count($elements) - 1] : $elements[0]));

          if($select_element)
          {
            $photo = '';
            $photo_class = '';
            if($fields['photo'] && $select_element[$fields['photo']])
            {
              $photo = '<div class="similar_post__item__photo" style="background-image: url(\'/u/i/' . Page::replaceImageSize($select_element[$fields['photo']], 'S') . '\');"></div>';
              $photo_class = ' similar_post__item_with_photo_' . ($i < $element_key ? 'prev' : 'next');
            }

            $title = $select_element[$fields['title']];
            if($cut && $cut['title'])
            {
              $str_cut = Page::strCut($select_element[$fields['title']], $cut['title']);
              $title = $str_cut[0];
            }

            $body = '';
            if($fields['body'])
            {
              if($cut['body'])
              {
                $body_str = Page::strCut($select_element[$fields['body']], $cut['body']);
                $body = '<div class="similar_post__item__body">' . strip_tags(htmlspecialchars_decode($body_str[0])) . '</div>';
              }
              else
              {
                $body = strip_tags(htmlspecialchars_decode($select_element[$fields['body']]));
              }
            }
          }

          echo '<a href="' . url_for('@' . $url . '?' . $fields['link'] . '=' . $select_element[$fields['link']]) . '" class="similar_post__item similar_post__item_' . ($i < $element_key ? 'prev' : 'next') . $photo_class . '">';

          if($select_element)
          {
            $arrow ='<div class="similar_post__item__arrow">' . ($i < $element_key ? 'Предыду' : 'Следую') . $string_end[$module] . '</div>';

            $link = '<div class="similar_post__item__title" >' . strip_tags(htmlspecialchars_decode($title)) . '</div>' . $body;

            echo $photo . $arrow . $link;
          }

          echo '</a>';
        }
      }
    }
  }
  echo '</div>';