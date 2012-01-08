<?php
/* ====================
Copyright (c) 2008-2011, Vladimir Sibirov.
All rights reserved. Distributed under BSD License.
[BEGIN_COT_EXT]
Hooks=page.tags
Tags=page.tpl:{PAGE_REVIEWS_TOP},{PAGE_REVIEWS_TOP_COUNT},{PAGE_REVIEWS_TOP_LATEST_DATE},{PAGE_REVIEWS_TOP_RECOMMENDED},{PAGE_REVIEWS_TOP_AVG_PRICE},{PAGE_REVIEWS_TOP_RATING},{PAGE_REVIEWS_DISPLAY},{PAGE_REVIEWS_COUNT},{PAGE_REVIEWS_LATEST_DATE},{PAGE_REVIEWS_RECOMMENDED},{PAGE_REVIEWS_AVG_PRICE},{PAGE_REVIEWS_CURRENCY},{PAGE_REVIEWS_RATING},{PAGE_REVIEWS_STARS},{PAGE_REVIEWS_XRATE},{PAGE_REVIEWS_TOP_XRATE}
[END_COT_EXT]
==================== */
defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('reviews', 'plug');

$perpage = $cfg['plugin']['reviews']['rows'];
list($pg_rv, $d_rv, $url_rv) = cot_import_pagenav('drv', $perpage);
list($write, $adm) = cot_auth('plug', 'reviews', 'WA');

$sql_ex = '';
for ($i = 1; $i <= count($xrates); $i++)
	$sql_ex .= ", AVG(rw_rate$i) AS rw_rate$i";

$rv_sql = $db->query("SELECT COUNT(*) AS rw_cnt, MAX(rw_date) AS rw_max, COUNT(rw_recommend) AS rw_rec, AVG(rw_price) AS rw_price, AVG(rw_rating) AS rw_rating, AVG(rw_rateavg) AS rw_rateavg $sql_ex FROM $db_reviews WHERE rw_page = $id");
$stat = $rv_sql->fetch();

// Get review stats
$t->assign(array(
	'PAGE_REVIEWS_TOP' => $L['Reviews'],
	'PAGE_REVIEWS_TOP_COUNT' => $L['Total_reviews'],
	'PAGE_REVIEWS_TOP_LATEST_DATE' => $L['Latest_review_date'],
	'PAGE_REVIEWS_TOP_RECOMMENDED' => $L['Recommended'],
	'PAGE_REVIEWS_TOP_AVG_PRICE' => $L['Avg_price'],
	'PAGE_REVIEWS_TOP_RATING' => $L['Overall_rating'],
	'PAGE_REVIEWS_COUNT' => $stat['rw_cnt'],
	'PAGE_REVIEWS_LATEST_DATE' => @date($cfg['dateformat'], $stat['rw_max'] + $usr['timezone'] * 3600),
	'PAGE_REVIEWS_RECOMMENDED' => $stat['rw_cnt'] > 0 ? round($stat['rw_rec'] / $stat['rw_cnt'], 2) * 100 : 0,
	'PAGE_REVIEWS_AVG_PRICE' => round($stat['rw_price'], $cfg['plugin']['reviews']['ppc']),
	'PAGE_REVIEWS_CURRENCY' => $cfg['plugin']['reviews']['cur'],
	'PAGE_REVIEWS_RATING' => round($stat['rw_rating'], $cfg['plugin']['reviews']['rpc']),
	'PAGE_REVIEWS_STARS' => cot_rc('icon_rating_stars', array('val' => round($stat['rw_rating']))),
	'PAGE_REVIEWS_RATING_AVG' => round($stat['rw_rateavg'], $cfg['plugin']['reviews']['rpc']),
	'PAGE_REVIEWS_STARS_AVG' => cot_rc('icon_rating_stars', array('val' => round($stat['rw_rateavg'])))
));

for ($i = 0; $i < count($xrates); $i++)
{
	$t->assign(array(
		'PAGE_REVIEWS_TOP_XRATE' => $xrates[$i],
		'PAGE_REVIEWS_XRATE' => round($stat['rw_rate' . ($i + 1)], $cfg['plugin']['reviews']['rpc'])
	));
	$t->parse('MAIN.PAGE_REVIEWS_XROW');
}

// Render reviews on page
$rv_sql = $db->query("SELECT r.*, u.*
FROM $db_reviews AS r LEFT JOIN $db_users AS u ON r.rw_user = u.user_id
WHERE rw_page = $id ORDER BY rw_id {$cfg['plugin']['reviews']['order']} LIMIT $d_rv,$perpage");
$rv_t = new XTemplate(cot_tplfile('reviews', 'plug'));

$pag_url_params = empty($al) ? "c={$pag['page_cat']}&id=$id" : "c={$pag['page_cat']}&al=$al";

$rv_pagenav = cot_pagenav('page', $pag_url_params, $d_rv, $stat['rw_cnt'], $perpage, 'drv');

$rv_t->assign(array(
	'REVIEWS_PAGNAV' => $rv_pagenav['main'],
	'REVIEWS_PAGE_PREV' => $rv_pagenav['prev'],
	'REVIEWS_PAGE_NEXT' => $rv_pagenav['next'],
	'REVIEWS_TOP_CURRENCY' => $cfg['plugin']['reviews']['cur']
));

while ($row = $rv_sql->fetch())
{
	if ($adm || $row['rw_user'] == $usr['id'] && ($usr['id'] > 0 || $usr['ip'] == $row['rw_userip']) && $sys['now'] < ($row['rw_date'] + $cfg['plugin']['reviews']['timeout'] * 60))
	{
		$pag_adm = '<a href="' . cot_url('plug', 'e=reviews&act=edit&id=' . $row['rw_id']) . '">' . $L['Edit']
		. '</a> <a href="' . cot_url('plug', 'e=reviews&act=del&id=' . $row['rw_id']) . '">' . $L['Delete'] . '</a>';
	}
	else
	{
		$pag_adm = '';
	}
	$rv_t->assign(cot_generate_usertags($row, 'REVIEWS_ROW_'));
	$rv_t->assign(array(
		'REVIEWS_ROW_ID' => $row['rw_id'],
		'REVIEWS_ROW_DATE' => cot_date('datetime_short', $row['rw_date']),
		'REVIEWS_ROW_RECOMMENDED' => $row['rw_recommend'] ? $L['Yes'] : $L['No'],
		'REVIEWS_ROW_TEXT' => cot_parse($row['rw_text']),
		'REVIEWS_ROW_PROS' => htmlspecialchars($row['rw_pros']),
		'REVIEWS_ROW_CONS' => htmlspecialchars($row['rw_cons']),
		'REVIEWS_ROW_PRICE' => round($row['rw_price'], 2),
		'REVIEWS_ROW_RATING' => round($row['rw_rating'], $cfg['plugin']['reviews']['rpc']),
		'REVIEWS_ROW_STARS' => cot_rc('icon_rating_stars', array('val' => round($row['rw_rating']))),
		'REVIEWS_ROW_RATING_AVG' => round($row['rw_rateavg'], $cfg['plugin']['reviews']['rpc']),
		'REVIEWS_ROW_STARS_AVG' => cot_rc('icon_rating_stars', array('val' => round($row['rw_rateavg']))),
		'REVIEWS_ROW_POSTERNAME' => $row['rw_user'] > 0 ? cot_build_user($row['rw_user'], htmlspecialchars($row['user_name'])) : htmlspecialchars($row['rw_username']),
		'REVIEWS_ROW_POSTERID' => $row['rw_user'],
		'REVIEWS_ROW_ADMIN' => $pag_adm
	));
	for ($i = 0; $i < count($xrates); $i++)
	{
		$rv_t->assign(array(
			'REVIEWS_ROW_XROW_TOP' => $xrates[$i],
			'REVIEWS_ROW_XROW_XRATE' => $row['rw_rate' . ($i + 1)],
			'REVIEWS_ROW_XROW_STARS' => cot_rc('icon_rating_stars', array('val' => round($row['rw_rate' . ($i + 1)])))
		));
		$rv_t->parse('MAIN.REVIEWS_ROW.REVIEWS_ROW_XROW');
	}
	$i = 0;
	foreach ($xfields as $code => $title)
	{
		$rv_t->assign(array(
			'REVIEWS_ROW_' . mb_strtoupper($code) => $row['rw_extra' . ($i + 1)],
			'REVIEWS_ROW_' . mb_strtoupper($code) . '_TITLE' => htmlspecialchars($title)
		));
		$i++;
	}
	$rv_t->parse('MAIN.REVIEWS_ROW');
}
$rv_sql->closeCursor();

require_once cot_incfile('forms');

for ($i = 0; $i < count($xrates); $i++)
{
	$rv_t->assign(array(
		'REVIEWS_FORM_XROW_TOP' => $xrates[$i],
		'REVIEWS_FORM_XROW_XRATE' => cot_selectbox(10, 'rate' . ($i + 1), range(0, 10), range(0, 10), false)
	));
	$rv_t->parse('MAIN.REVIEWS_FORM.REVIEWS_FORM_XROW');
}

if ($write)
{
	// Review input form
	$rv_t->assign(array(
		'REVIEWS_FORM_ACTION' => cot_url('plug', 'e=reviews&act=add&id=' . $id),
		'REVIEWS_FORM_RATING' => cot_selectbox(10, 'rating', range(0, 10), range(0, 10), false),
		'REVIEWS_FORM_RECOMMEND' => cot_radiobox(1, 'recommend', array(1, 0), array($L['Yes'], $L['No'])),
		'REVIEWS_FORM_TEXT' => cot_textarea('rtext', $rtext, 12, 50, 'class="editor"'),
		'REVIEWS_FORM_EDITHINT' => cot_rc('Edithint', array('time' => cot_build_timegap($sys['now'] - $cfg['plugin']['reviews']['timeout'] * 60, $sys['now']))),
	));
	if ($usr['id'] == 0)
	{
		// Guest
		$rv_t->assign(array(
			'REVIEWS_FORM_USERNAME' => cot_inputbox('text', 'username'),
			'REVIEWS_FORM_CAPTCHA_IMG' => mcaptcha_generate(),
			'REVIEWS_FORM_CAPTCHA_INPUT' => cot_inputbox('text', 'rverify', '', 'size="4"')
		));
		$rv_t->parse('MAIN.REVIEWS_FORM.REVIEWS_FORM_GUEST');
	}
	$i = 0;
	foreach ($xfields as $code => $title)
	{
		$rv_t->assign(array(
			'REVIEWS_FORM_' . mb_strtoupper($code) => cot_inputbox('text', "extra_$code"),
			'REVIEWS_FORM_' . mb_strtoupper($code) . '_TITLE' => htmlspecialchars($title)
		));
		$i++;
	}
	cot_display_messages($rv_t, 'MAIN.REVIEWS_FORM');
	$rv_t->parse('MAIN.REVIEWS_FORM');
}

$rv_t->parse('MAIN');
$t->assign('PAGE_REVIEWS_DISPLAY', $rv_t->text('MAIN'));

?>