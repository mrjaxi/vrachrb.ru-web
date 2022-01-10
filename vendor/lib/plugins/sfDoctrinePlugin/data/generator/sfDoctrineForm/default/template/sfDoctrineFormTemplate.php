[?php

/**
 * <?php echo $this->table->getOption('name') ?> form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class <?php echo $this->table->getOption('name') ?>Form extends Base<?php echo $this->table->getOption('name') ?>Form
{
<?php if ($parent = $this->getParentModel()): ?>
  /**
   * @see <?php echo $parent ?>Form
   */
  public function configure()
  {
    parent::configure();
  }
<?php else: ?>
  public function configure()
  {
<?php
    $imp = array();
    $exclude = array('id', 'updated_at');
    foreach ($this->getColumns() as $column)
    {
      if(!in_array($column->getFieldName(), $exclude))
      {
        $imp[] = "'" . $column->getFieldName() . "'";
      }
    }
    ?>
    $this->useFields(array(<?php echo implode(', ', $imp);?>));
  }
<?php endif; ?>
}
