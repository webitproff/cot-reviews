<!-- BEGIN: MAIN -->
<h3>{PHP.L.Reviews}</h3>
<table class="cells">
<!-- BEGIN: REVIEWS_ROW -->
	<tr>
		<td>
			<strong>{REVIEWS_ROW_POSTERNAME}</strong>
		</td>
		<td>{REVIEWS_ROW_DATE} {PHP.L.Recommended}: {REVIEWS_ROW_RECOMMENDED} {PHP.L.Rating}: {REVIEWS_ROW_RATING} {REVIEWS_ROW_STARS} {PHP.L.Price_paid}: {REVIEWS_ROW_PRICE} {REVIEWS_TOP_CURRENCY}</td>
	</tr>
	<tr>
		<td>{REVIEWS_ROW_MAINGRP}
			<br />{REVIEWS_ROW_AVATAR}
			<br />{PHP.L.Location}: {REVIEWS_ROW_LOCATION} {REVIEWS_ROW_COUNTRYFLAG}
		</td>
		<td>
			<strong>{PHP.L.Pros}:</strong> {REVIEWS_ROW_PROS}
			<br />
			<strong>{PHP.L.Cons}:</strong> {REVIEWS_ROW_CONS}
			<br />
			<strong>{REVIEWS_ROW_EXTRA1_TITLE}:</strong> {REVIEWS_ROW_EXTRA1}
			<br />
			<p>{REVIEWS_ROW_TEXT}</p>
<!-- BEGIN: REVIEWS_ROW_XROW -->
			<strong>{REVIEWS_ROW_XROW_TOP}: </strong> {REVIEWS_ROW_XROW_XRATE}
			<br />
<!-- END: REVIEWS_ROW_XROW -->
			{REVIEWS_ROW_ADMIN}
		</td>
	</tr>
<!-- END: REVIEWS_ROW -->
</table>

<div class="pages">{REVIEWS_PAGE_PREV} {REVIEWS_PAGNAV} {REVIEWS_PAGE_NEXT}</div>

<!-- BEGIN: REVIEWS_FORM -->
{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
<form action="{REVIEWS_FORM_ACTION}" name="newreview" method="post">
	<table class="cells">
<!-- BEGIN: REVIEWS_FORM_GUEST -->
		<tr>
			<td>{PHP.L.Name}</td>
			<td>{REVIEWS_FORM_USERNAME}</td>
		</tr>
		<tr>
			<td>{REVIEWS_FORM_CAPTCHA_IMG}</td>
			<td>{REVIEWS_FORM_CAPTCHA_INPUT}</td>
		</tr>
<!-- END: REVIEWS_FORM_GUEST -->
		<tr>
			<td>{PHP.L.Recommend}</td>
			<td>{REVIEWS_FORM_RECOMMEND}</td>
		</tr>
		<tr>
			<td>{PHP.L.Price_paid}</td>
			<td>
				<input type="text" name="price" value="{REVIEWS_FORM_PRICE}" /> {REVIEWS_TOP_CURRENCY}
			</td>
		</tr>
		<tr>
			<td colspan="2">
			{REVIEWS_FORM_TEXT}
			</td>
		</tr>
		<tr>
			<td>{PHP.L.Pros}</td>
			<td>
				<input type="text" size="80" name="pros" value="{REVIEWS_FORM_PROS}" />
			</td>
		</tr>
		<tr>
			<td>{PHP.L.Cons}</td>
			<td>
				<input type="text" size="80" name="cons" value="{REVIEWS_FORM_CONS}" />
			</td>
		</tr>
		<tr>
			<td>{REVIEWS_FORM_EXTRA1_TITLE}</td>
			<td>{REVIEWS_FORM_EXTRA1}</td>
		</tr>
<!-- BEGIN: REVIEWS_FORM_XROW -->
		<tr>
			<td>{REVIEWS_FORM_XROW_TOP}</td>
			<td>{REVIEWS_FORM_XROW_XRATE}</td>
		</tr>
<!-- END: REVIEWS_FORM_XROW -->
		<tr>
			<td>{PHP.L.Overall_rating}</td>
			<td>{REVIEWS_FORM_RATING}</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="help">{REVIEWS_FORM_EDITHINT}</div>
				<input type="submit" value="{PHP.L.Submit}" />
			</td>
		</tr>
	</table>
</form>
{REVIEWS_BACK}
<!-- END: REVIEWS_FORM -->
<!-- END: MAIN -->