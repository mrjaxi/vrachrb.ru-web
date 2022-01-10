<?php
echo mb_substr($complaint->getQuestion()->getBody(), 0, 300) . '...';