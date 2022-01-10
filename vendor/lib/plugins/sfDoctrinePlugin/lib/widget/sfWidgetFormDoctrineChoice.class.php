<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormDoctrineChoice represents a choice widget for a model.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfWidgetFormDoctrineChoice.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfWidgetFormDoctrineChoice extends sfWidgetFormChoice
{
    /**
     * @see sfWidget
     */
    public function __construct($options = array(), $attributes = array())
    {
        $options['choices'] = array();
        parent::__construct($options, $attributes);
    }

    protected $tree = array();
    protected $tree_all = array();

    protected function buildTree($parent_id = null, $level = 0, $disabled = false)
    {
        $chs = array();
        foreach ($this->tree_all as $k => $v) {
            $disabled_item = $disabled === true || $k == $this->getOption('disabled');
            if ($v['parent_id'] == $parent_id) {


                $chs[$k] = array(
                    'value' => str_repeat('&nbsp;&nbsp;&nbsp;', $level) . $v['value'],
                    'level' => $level,
                    'children' => $k != '' ? $this->buildTree($k, $level + 1, $disabled_item) : array(),
                    'disabled' => $disabled_item
                );
            }
        }
        return $chs;
    }

    protected function configure($options = array(), $attributes = array())
    {
        $this->addRequiredOption('model');
        $this->addOption('disabled', false);
        $this->addOption('parent_id', null);
        $this->addOption('ids', array());
        $this->addOption('add_empty', false);
        $this->addOption('method', '__toString');
        $this->addOption('key_method', 'getPrimaryKey');
        $this->addOption('order_by', null);
        $this->addOption('query', null);
        $this->addOption('multiple', false);
        $this->addOption('table_method', null);
        $this->addOption('expanded', false);
        $this->addOption('array', false);

        parent::configure($options, $attributes);
    }

    /**
     * Returns the choices associated to the model.
     *
     * @return array An array of choices
     */
    public function getChoices()
    {
        $choices = array();
        if (false !== $this->getOption('add_empty')) {
            $choices[''] = true === $this->getOption('add_empty') ? '—' : $this->getOption('add_empty');
        }

        $tree = false;

        if ($model = $this->getOption('model')) {
            $object = new $model();
            $tree = isset($object['parent_id']);
        }


        if (null === $this->getOption('table_method')) {
            $query = null === $this->getOption('query') ? Doctrine_Core::getTable($this->getOption('model'))->createQuery() : $this->getOption('query');
            if ($order = $this->getOption('order_by')) {
                $query->addOrderBy($order[0] . ' ' . $order[1]);
            }
            $parent_id = $this->getOption('parent_id');
            $ids = $this->getOption('ids');
            if ($parent_id !== null) {
                $query->select("id, title, parent_id");
                $query->where('(parent_id IS NULL AND id = ' . $parent_id . (count($ids) > 0 ? ' OR id IN(' . implode(",", $ids) . ')' : '') . ') OR parent_id = ' . $parent_id);
            } else if ($this->getOption('array')) {
                if ($tree) {
                    $query->select("id, title, parent_id");
                } else {
                    $query->select("id, title");
                }
            }

            $objects = $this->getOption('array') ? $query->fetchArray() : $query->execute();

        } else {
            $tableMethod = $this->getOption('table_method');
            $results = Doctrine_Core::getTable($this->getOption('model'))->$tableMethod();

            if ($results instanceof Doctrine_Query) {
                $objects = $results->execute();
            } else if ($results instanceof Doctrine_Collection) {
                $objects = $results;
            } else if ($results instanceof Doctrine_Record) {
                $objects = new Doctrine_Collection($this->getOption('model'));
                $objects[] = $results;
            } else {
                $objects = array();
            }

        }



        if ($tree) {
            $this->tree_all[''] = (true === $this->getOption('add_empty') ? array('value' => '—', 'parent_id' => null) : array('value' => $this->getOption('add_empty'), 'parent_id' => null));
        }
        $method = $this->getOption('method');
        $keyMethod = $this->getOption('key_method');
        if ($this->getOption('array')) {
            foreach ($objects as $object) {
                $choices[$object['id']] = $object['title'];
                if ($tree) {
                    $this->tree_all[$object['id']] = array('value' => $object['title'], 'parent_id' => $object['parent_id']);
                }
            }
        } else {
            foreach ($objects as $object) {
                $choices[$object->$keyMethod()] = $object->$method();
            }
        }

        if (count($this->tree_all) > 0) {
            $choices = $this->buildTree(null, 0, $this->getOption('disabled'));
        }


        return $choices;
    }
}
