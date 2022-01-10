<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * 
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormSchemaFormatterTable.class.php 5995 2007-11-13 15:50:03Z fabien $
 */
class sfWidgetFormSchemaFormatterTable extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<div>\n  %label%<span class=\"br5\"></span>\n  %field%%help%\n%error%\n%hidden_fields%</div><span class=\"br10\"></span>\n",
    $errorRowFormat  = '<div class="error">%errors%</div>',
    $helpFormat      = '<small style="color:#585858;position:relative;top:3px;">%help%</small>',
    $decoratorFormat = '<div class="inline_decorator">%content%</div>';
  /*
  protected
    $rowFormat       = "<tr>\n  <th>%label%</th>\n  <td>%error%%field%%help%%hidden_fields%</td>\n</tr>\n",
    $errorRowFormat  = "<tr><td colspan=\"2\">\n%errors%</td></tr>\n",
    $helpFormat      = '<br />%help%',
    $decoratorFormat = "<table>\n  %content%</table>";*/
}
