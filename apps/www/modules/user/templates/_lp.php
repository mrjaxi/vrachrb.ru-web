<?php
echo '<script type="text/javascript" src="/js/pushstream.js"></script>';
echo '<script type="text/javascript" src="/js/lp_vrb.js"></script>';
echo '<script type="text/javascript">lp_vrb.init(' . $sf_user->getUserId() . ');</script>';
?>