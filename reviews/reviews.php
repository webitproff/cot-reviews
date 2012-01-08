<?php
/* ====================
Copyright (c) 2008-2011, Vladimir Sibirov.
All rights reserved. Distributed under BSD License.
[BEGIN_COT_EXT]
Hooks=standalone
Tags=reviews.tpl:{REVIEWS_PAGNAV},{REVIEWS_PAGE_PREV},{REVIEWS_PAGE_NEXT},{REVIEWS_TOP_CURRENCY},{REVIEWS_ROW_ID},{REVIEWS_ROW_DATE},{REVIEWS_ROW_RECOMMENDED},{REVIEWS_ROW_TEXT},{REVIEWS_ROW_PROS},{REVIEWS_ROW_CONS},{REVIEWS_ROW_PRICE},{REVIEWS_ROW_STARS},{REVIEWS_ROW_POSTERNAME},{REVIEWS_ROW_POSTERID},{REVIEWS_ROW_MAINGRPSTARS},{REVIEWS_ROW_MAINGRPICON},{REVIEWS_ROW_USERTEXT},{REVIEWS_ROW_AVATAR},{REVIEWS_ROW_GENDER},{REVIEWS_ROW_COUNTRY},{REVIEWS_ROW_COUNTRYFLAG},{REVIEWS_ROW_XROW_TOP},{REVIEWS_ROW_XROW_XRATE},{REVIEWS_FORM_ACTION},{REVIEWS_FORM_RATING},{REVIEWS_FORM_TEXTBOXER},{REVIEWS_FORM_XROW_TOP},{REVIEWS_FORM_XROW_XRATE},{REVIEWS_BACK}
[END_COT_EXT]
==================== */
defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('page', 'module');
require_once cot_incfile('reviews', 'plug');

$act = cot_import('act', 'G', 'ALP');
$id = cot_import('id', 'G', 'INT');
$upd = cot_import('upd', 'G', 'BOL');

list($write, $adm) = cot_auth('plug', 'reviews', 'WA');
cot_block($write);

$out['subtitle'] = $L['Reviews'];

if ($id > 0)
{
	if ($act == 'add')
	{
		$r = array();
		$r['rw_text'] = cot_import('rtext', 'P', 'HTM');
		$r['rw_pros'] = cot_import('pros', 'P', 'TXT');
		$r['rw_cons'] = cot_import('cons', 'P', 'TXT');
		$r['rw_price'] = floatval(trim(str_replace(',', '.', cot_import('price', 'P', 'TXT'))));
		$r['rw_recommend'] = (int) cot_import('recommend', 'P', 'BOL');
		$r['rw_rating'] = cot_import('rating', 'P', 'INT');
		$rate_sum = 0;
		for ($i = 1; $i <= count($xrates); $i++)
		{
			$r["rw_rate$i"] = cot_import("rate$i", 'P', 'INT');
			if ($r["rw_rate$i"] < 1 || $r["rw_rate$i"] > 10)
				$r["rw_rate$i"] = 5;
			$rate_sum += $r["rw_rate$i"];
		}
		$r['rw_rateavg'] = count($xrates) > 0 ? round($rate_sum / count($xrates), 2) : 0;
		
		if ($r['rw_rating'] < 1 || $r['rw_rating'] > 10)
		{
			$r['rw_rating'] = 5;
		}
		
		$i = 0;
		foreach ($xfields as $code => $title)
		{
			$r['rw_extra' . ($i + 1)] = cot_import("extra_$code", 'P', 'TXT');
			$i++;
		}
		
		if ($usr['id'] == 0 && !mcaptcha_validate(cot_import('rverify', 'P', 'INT')))
		{
			cot_error('captcha_verification_failed');
		}
		
		if (empty($r['rw_text']))
		{
			// ERR
			cot_error('page_textmissing');
		}
		elseif (!cot_error_found())
		{
			$r['rw_date'] = $sys['now'];
			$r['rw_page'] = $id;
			$r['rw_user'] = $usr['id'];
			$r['rw_username'] = $usr['id'] > 0 ? $usr['name'] : cot_import('username', 'P', 'TXT');
			$r['rw_userip'] = $usr['ip'];
			$db->insert($db_reviews, $r);
		}
		$pag = $db->query("SELECT page_id, page_cat, page_alias FROM $db_pages WHERE page_id = ?", array($id))->fetch();
		$pag_urlp = empty($pag['page_alias']) ? array('c' => $pag['page_cat'], 'id' => $pag['page_id']) : array('c' => $pag['page_cat'], 'al' => $pag['page_alias']);
		cot_redirect(cot_url('page', $pag_urlp, '', true));
		exit;
	}
	elseif ($act == 'del')
	{
		$rev = $db->query("SELECT * FROM $db_reviews WHERE rw_id = $id")->fetch();
		if ($rev['rw_user'] == $usr['id'] && $sys['now'] < ($rev['rw_date'] + $cfg['plugin']['reviews']['timeout'] * 60) || $adm)
		{
			$db->delete($db_reviews, "rw_id = $id");
		}
		$pag = $db->query("SELECT page_id, page_cat, page_alias FROM $db_pages WHERE page_id = ?", array($rev['rw_page']))->fetch();
		$pag_urlp = empty($pag['page_alias']) ? array('c' => $pag['page_cat'], 'id' => $pag['page_id']) : array('c' => $pag['page_cat'], 'al' => $pag['page_alias']);
		cot_redirect(cot_url('page', $pag_urlp, '', true));
		exit;
	}
	elseif ($act == 'edit')
	{
		$row = $db->query("SELECT * FROM $db_reviews WHERE rw_id = $id")->fetch();
		if (empty($row) || (($row['rw_user'] != $usr['id'] || $sys['now'] >= ($row['rw_date'] + $cfg['plugin']['reviews']['timeout'] * 60)) && !$adm))
		{
			cot_die(); // ERR
		}
		if ($upd)
		{
			// Apply changes
			$row['rw_text'] = cot_import('rtext', 'P', 'HTM');
			$row['rw_username'] = $usr['id'] > 0 ? $usr['name'] : cot_import('username', 'P', 'TXT');
			$row['rw_pros'] = cot_import('pros', 'P', 'TXT');
			$row['rw_cons'] = cot_import('cons', 'P', 'TXT');
			$row['rw_price'] = floatval(trim(str_replace(',', '.', cot_import('price', 'P', 'TXT'))));
			$row['rw_recommend'] = (int) cot_import('recommend', 'P', 'BOL');
			$row['rw_rating'] = cot_import('rating', 'P', 'INT');
			if ($row['rw_rating'] < 1 || $row['rw_rating'] > 10)
			{
				$row['rw_rating'] = 5;
			}
			$rate_sum = 0;
			for ($i = 1; $i <= count($xrates); $i++)
			{
				$row["rw_rate$i"] = cot_import("rate$i", 'P', 'INT');
				if ($row["rw_rate$i"] < 1 || $row["rw_rate$i"] > 10)
					$row["rw_rate$i"] = 5;
				$rate_sum += $row["rw_rate$i"];
			}
			$row['rw_rateavg'] = count($xrates) > 0 ? round($rate_sum / count($xrates), 2) : 0;
			
			$i = 0;
			foreach ($xfields as $code => $title)
			{
				$row['rw_extra' . ($i + 1)] = cot_import("extra_$code", 'P', 'TXT');
				$i++;
			}
			
			if (empty($row['rw_text']))
			{
				// ERR
				cot_error('page_textmissing');
			}
			else
			{
				$rcm = $recomm ? 1 : 'NULL';
				$db->update($db_reviews, $row, "rw_id = $id");
			}
		}
		
		require_once cot_incfile('forms');

		$t = new XTemplate(cot_tplfile('reviews', 'plug'));
		
		$pag = $db->query("SELECT page_id, page_cat, page_alias FROM $db_pages WHERE page_id = ?", array($row['rw_page']))->fetch();
		$pag_urlp = empty($pag['page_alias']) ? array('c' => $pag['page_cat'], 'id' => $pag['page_id']) : array('c' => $pag['page_cat'], 'al' => $pag['page_alias']);
		
		for ($i = 0; $i < count($xrates); $i++)
		{
			$t->assign(array(
				'REVIEWS_FORM_XROW_TOP' => $xrates[$i],
				'REVIEWS_FORM_XROW_XRATE' => cot_selectbox($row['rw_rate' . ($i + 1)], 'rate' . ($i + 1), range(0, 10), range(0, 10), false)
			));
			$t->parse('MAIN.REVIEWS_FORM.REVIEWS_FORM_XROW');
		}
		// Review input form
		$t->assign(array(
			'REVIEWS_TOP_CURRENCY' => $cfg['plugin']['reviews']['cur'],
			'REVIEWS_FORM_ACTION' => cot_url('plug', 'e=reviews&act=edit&upd=1&id=' . $id),
			'REVIEWS_FORM_RATING' => cot_selectbox($row['rw_rating'], 'rating', range(0, 10), range(0, 10), false),
			'REVIEWS_FORM_RECOMMEND' => cot_radiobox($row['rw_recommend'], 'recommend', array(1, 0), array($L['Yes'], $L['No'])),
			'REVIEWS_FORM_PRICE' => $row['rw_price'],
			'REVIEWS_FORM_PROS' => htmlspecialchars($row['rw_pros']),
			'REVIEWS_FORM_CONS' => htmlspecialchars($row['rw_cons']),
			'REVIEWS_FORM_TEXT' => cot_textarea('rtext', $row['rw_text'], 12, 50, 'class="editor"'),
			'REVIEWS_FORM_USERNAME' => cot_inputbox('text', 'username', $row['rw_username']),
			'REVIEWS_FORM_EDITHINT' => cot_rc('Edithint', array('time' => cot_build_timegap($sys['now'], $cfg['plugin']['reviews']['timeout'] * 60 + $row['rw_date']))),
			'REVIEWS_BACK' => '<a href="' . cot_url('page', $pag_urlp) . '">' . $L['Back'] . '</a>'
		));
		$i = 0;
		foreach ($xfields as $code => $title)
		{
			$t->assign(array(
				'REVIEWS_FORM_' . mb_strtoupper($code) => cot_inputbox('text', "extra_$code", $row['rw_extra' . ($i + 1)]),
				'REVIEWS_FORM_' . mb_strtoupper($code) . '_TITLE' => htmlspecialchars($title)
			));
			$i++;
		}
		cot_display_messages($t, 'MAIN.REVIEWS_FORM');
		$t->parse('MAIN.REVIEWS_FORM');

		if ($upd)
		{
			cot_redirect(cot_url('page', $pag_urlp, '', true));
			exit;
		}
	}
	else
	{
		// Display as single review?
	}
}
else
{
	// Display review stats?
}

?>