[?php if ($sf_user->hasFlash('notice')): ?]
<i class="lui__notice[?php echo (!$sf_request->isXmlHttpRequest() ? ' lui__notice__no_ajax' : '');?]">&nbsp;&nbsp;&nbsp;[?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?]</i>
[?php endif; ?]

[?php if ($sf_user->hasFlash('error')): ?]
<i class="lui__error[?php echo (!$sf_request->isXmlHttpRequest() ? ' lui__error__no_ajax' : '');?]">&nbsp;&nbsp;&nbsp;[?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?]</i>
[?php endif; ?]
