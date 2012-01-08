<?php
/**
 * Copyright (c) 2008-2011, Vladimir Sibirov.
 * All rights reserved. Distributed under BSD License.
 */

defined('COT_CODE') or die('Wrong URL.');

require_once cot_langfile('reviews', 'plug');

$db_reviews = isset($db_reviews) ? $db_reviews : $db_x . 'reviews';

// Get rating types
$xrates = array();
foreach (preg_split('#\r?\n#', $cfg['plugin']['reviews']['rates']) as $xr)
{
	if (trim($xr) != '')
		$xrates[] = trim($xr);
}
// Get extra fields
$xfields = array();
foreach (preg_split('#\r?\n#', $cfg['plugin']['reviews']['extra']) as $xr)
{
	$tmp = explode('|', trim($xr));
	if (count($tmp) == 2)
		$xfields[$tmp[0]] = $tmp[1];
}

?>
