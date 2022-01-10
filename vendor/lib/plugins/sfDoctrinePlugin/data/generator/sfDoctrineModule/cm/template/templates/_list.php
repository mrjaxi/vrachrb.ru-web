<div class="menu_l cm_menu_l">
[?php if (!$pager->getNbResults()): ?]

[?php else: ?]

[?php foreach ($pager->getResults() as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?]

[?php

?]

<div class="cm_menu_l__item scroller__loader" data-href="[?php echo url_for('<?php echo $this->getSingularName();?>/edit?id=' . $<?php echo $this->getSingularName();?>->getPrimaryKey());?]">
  <i class="txt_color_grey">[?php echo Page::rusDate($<?php echo $this->getSingularName();?>->getCreatedAt());?]</i>
  <i class="br5"></i>
  [?php echo $<?php echo $this->getSingularName();?>?]
</div>
[?php endforeach; ?]
[?php endif; ?]
</div>
