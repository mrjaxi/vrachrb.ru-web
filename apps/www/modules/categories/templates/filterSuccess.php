<?php
if($sf_request->isMethod('post'))
{
  include_component('specialist', 'specialist', array('view' => 'vertical', 'categories_id' => $sp_param));
  echo ':delimiter:';
  include_component('article', 'article', array('categories' => 'y', 'categories_id' => $sp_param));
  echo ':delimiter:';
  include_component('question_answer', 'question_index', array('specialty_id' => $sp_param, 'categories' => 'y', 'categories_filter_ajax' => 'y', 'sp_limit' => $sp_limit));
  echo ':delimiter:';
  include_component('tip', 'tip', array('specialty_id' => $sp_param, 'categories' => 'y'));
}
else
{
  use_javascript('masonry.pkgd.min.js');
  include_partial('categories_index', array('specialty_id' => $sp_param));
}
?>