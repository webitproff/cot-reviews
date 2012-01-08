<?php
/* ====================
Copyright (c) 2008-2011, Vladimir Sibirov.
All rights reserved. Distributed under BSD License.
[BEGIN_COT_EXT]
Hooks=page.list.loop
Tags=page.list.tpl:{LIST_ROW_REVIEWS_RATING},{LIST_ROW_REVIEWS_STARS},{LIST_ROW_REVIEWS_RATING_AVG},{LIST_ROW_REVIEWS_STARS_AVG}
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

$t->assign(array(
	'LIST_ROW_REVIEWS_RATING' => round($pag['page_review_rating'], $cfg['plugin']['reviews']['rpc']),
	'LIST_ROW_REVIEWS_STARS' => cot_rc('icon_rating_stars', array('val' => round($pag['page_review_rating']))),
	'LIST_ROW_REVIEWS_RATING_AVG' => round($pag['page_object_rating'], $cfg['plugin']['reviews']['rpc']),
	'LIST_ROW_REVIEWS_STARS_AVG' => cot_rc('icon_rating_stars', array('val' => round($pag['page_object_rating']))),
));

?>
