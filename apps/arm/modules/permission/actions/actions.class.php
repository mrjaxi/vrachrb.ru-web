<?php

require_once dirname(__FILE__).'/../lib/permissionGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/permissionGeneratorHelper.class.php';

class permissionActions extends autoPermissionActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $actions = Permission::getBasePermission();
    
    $actions_stop = array();
    foreach($actions as $ak => $av)
    {
      $actions_stop = array_merge($actions_stop, explode('|', $ak));
    }
    
    $exist = array();
    $new = array();
    
    $permissions = Doctrine_Query::create()
      ->select("p.*")
      ->from('Permission p')
      ->execute()
    ;
    
    foreach($permissions as $permission)
    {
      $exist[] = $permission['id'];
    }
    
    
    $parser = new sfYamlParser();
    $modules = glob(sfConfig::get('sf_app_module_dir') . '/*');
    $links = array();
    foreach($modules as $k => $v)
    {
      if(basename($v) == 'user' || basename($v) == 'user_group' || basename($v) == 'permission' || basename($v) == 'page')
      {
        continue;
      }
      if(file_exists($v . '/config/generator.yml'))
      {
        $p = $parser->parse(file_get_contents($v . '/config/generator.yml'));
        foreach($actions as $ak => $av)
        {
          if($ak == 'batch|batchMerge' && !isset($p['generator']['param']['config']['list']['batch_actions']['_merge']))
          {
            continue;
          }
          
          $perm = Doctrine::getTable('Permission')->findOneByCredential(basename($v) . '-' . $ak);
          if(!$perm)
          {
            $perm = new Permission();
            $perm->setCredential(basename($v) . '-' . $ak);
          }
          $perm->setDescription(strtolower($p['generator']['param']['config']['list']['title'] . ':' . $av));
          $perm->save();
          $new[] = $perm->getId();
        }
        
        $more_actions = array();
        
        if(file_exists($v . '/actions/actions.class.php'))
        {
          $lines = file($v . '/actions/actions.class.php');

          foreach($lines as $line_k => $line)
          {
            $line = trim($line);
            $actionpos = strpos($line, 'public function execute');
            if($actionpos !== false)
            {
              $name = substr($line, 23, strpos($line, '(') - 23);
              $name = strtolower(preg_replace(array('/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'), '\\1_\\2', $name));
              if(!in_array($name, $actions_stop))
              {
                $title = $name;
                $titleline = trim($lines[$line_k - 1]);
                $titlelinepos = strpos($titleline, '// @title');
                if($titlelinepos !== false)
                {
                  $titletmp = substr($titleline, 9);
                  if($titletmp != '')
                  {
                    $title = trim($titletmp);
                  }
                }
                $more_actions[strtolower($name)] = $title;
              }
            }
          }
        }
        foreach($more_actions as $ak => $av)
        {
          $perm = Doctrine::getTable('Permission')->findOneByCredential(basename($v) . '-' . $ak);
          if(!$perm)
          {
            $perm = new Permission();
            $perm->setCredential(basename($v) . '-' . $ak);
          }
          $perm->setDescription(strtolower($p['generator']['param']['config']['list']['title'] . ':' . $av));
          $perm->save();
          $new[] = $perm->getId();
        }
      }
    }
    if(count($new) > 0)
    {
      $diff = array_diff($exist, $new);
      if(count($diff) > 0)
      {
        $permissions = Doctrine_Query::create()
          ->select("p.*")
          ->from('Permission p')
          ->whereIn("p.id", $diff)
          ->execute()
        ;
        foreach($permissions as $permission)
        {
          $user_permissions = Doctrine_Query::create()
            ->delete("up.*")
            ->from('UserPermissions up')
            ->where("up.permission_id = ?", $permission['id'])
            ->execute()
          ;
          $user_group_permissions = Doctrine_Query::create()
            ->delete("ugp.*")
            ->from('UserGroupPermissions ugp')
            ->where("ugp.permission_id = ?", $permission['id'])
            ->execute()
          ;
          $permission->delete();
        }
      }
    }

    parent::executeIndex($request);
  }
}
