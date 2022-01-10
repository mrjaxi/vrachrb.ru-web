<?php
class sfWidgetFormSchemaFormatterInline extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<div>\n  %label%<span class=\"br5\"></span>\n  %field%%help%\n%error%\n%hidden_fields%</div><span class=\"br10\"></span>\n",
    $errorRowFormat  = '<div class="error">%errors%</div>',
    $helpFormat      = '&nbsp;&nbsp;<small style="color:#585858;position:relative;top:3px;">%help%</small>',
    $decoratorFormat = '<div class="inline_decorator">%content%</div>';
}
