<?php
if($prompt->getCreatedAt())
{
  echo Page::rusDate($prompt->getCreatedAt());
}