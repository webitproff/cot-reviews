<?php
/* ====================
Copyright (c) 2008-2011, Vladimir Sibirov.
All rights reserved. Distributed under BSD License.
[BEGIN_COT_EXT]
Hooks=page.list.query
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('reviews', 'plug');

// Modify SQL query to calculate ratings
$join_columns .= ", (SELECT AVG(rw_rating) FROM $db_reviews WHERE rw_page = p.page_id) AS page_review_rating , (SELECT AVG(rw_rateavg) FROM $db_reviews WHERE rw_page = p.page_id) AS page_object_rating";

?>
