# cot-reviews
Page reviews plugin for Cotonti
Reviews with ratings and recommendations for Pages representing goods or other kinds of reviewable material
1. Features

    Members can add reviews to pages
    Each review can contain a comment, recommendation (yes/no), overall rating, up to 9 custom ratings, pros and cons, up to 5 extra fields
    Edit/Delete functionality
    Averages and statistics for page
    Pagination and configurable order (newest first or chronological)

2. Download

Siena version is available here
3. Screenshots
Item overview:

Reviews:

4. Installation

    Unpack into plugins folder and install in Administration => Plugins
    Edit plugin configuration
    Edit your page.tpl for reviewable category of pages. Example TPL snippet:

    		`<div class="block">
    			<ul>
    				<li>{PAGE_REVIEWS_TOP_COUNT}: {PAGE_REVIEWS_COUNT}</li>
    				<li>{PAGE_REVIEWS_TOP_LATEST_DATE}: {PAGE_REVIEWS_LATEST_DATE}</li>
    				<li>{PAGE_REVIEWS_TOP_RECOMMENDED}: {PAGE_REVIEWS_RECOMMENDED}%</li>
    				<li>{PAGE_REVIEWS_TOP_AVG_PRICE}: {PAGE_REVIEWS_AVG_PRICE} {PAGE_REVIEWS_CURRENCY}</li>
    				<li>{PAGE_REVIEWS_TOP_RATING}: {PAGE_REVIEWS_RATING} {PAGE_REVIEWS_STARS}</li>
    				<!-- BEGIN: PAGE_REVIEWS_XROW -->
    				<li>{PAGE_REVIEWS_TOP_XRATE}: {PAGE_REVIEWS_XRATE}</li>
    				<!-- END: PAGE_REVIEWS_XROW -->
    			</ul>
    			{PAGE_REVIEWS_DISPLAY}
    		</div>`

Edit reviews.tpl and put it into your skin/plugins directory if necessary.
