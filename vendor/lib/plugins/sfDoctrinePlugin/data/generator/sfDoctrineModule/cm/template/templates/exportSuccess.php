[?php
$sf_resuest->getResponse()->setHttpHeader('Content-type', 'application/octet-stream');
$sf_resuest->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename="Export ' . date('d.m.Y') . '.xlsx"');
$sf_resuest->getResponse()->setHttpHeader('X-Accel-Redirect', '/u/p/_.xlsx');
?]