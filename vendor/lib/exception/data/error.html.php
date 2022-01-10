<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php $path = sfConfig::get('sf_relative_url_root', preg_replace('#/[^/]+\.php5?$#', '', isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : (isset($_SERVER['ORIG_SCRIPT_NAME']) ? $_SERVER['ORIG_SCRIPT_NAME'] : ''))) ?>

<html style="height:100%" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ошибка <?php echo $code; ?></title>

    <style type="text/css">
        * {
            font-family: "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            font-size: 14px;
            line-height: 22px;
        }

        h1 {
            font-weight: normal;
            font-size: 32px;
        }
    </style>
</head>
<body style="height:100%;margin: 0;">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center" valign="middle">
            <table cellpadding="20" cellspacing="0">
                <tr>
                    <td><img src="/i/sticker.png" height="200" width="200"/></td>
                    <td>
                        <h1>Ошибка <?php echo $code; ?></h1>
                        <?php
                        if ($code == 500) {
                            echo 'Сервис временно недоступен.<br />Пожалуйста, попробуйте обновить страницу чуть позже.';
                        }
                        ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
