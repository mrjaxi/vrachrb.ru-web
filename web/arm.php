<?php
//exec('../symfony cc');
$GLOBALS['t'] = microtime(true);


require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('arm', 'dev', true);
sfContext::createInstance($configuration)->dispatch();