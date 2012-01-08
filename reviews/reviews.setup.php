<?php
/* ====================
Copyright (c) 2008-2011, Vladimir Sibirov.
All rights reserved. Distributed under BSD License.
[BEGIN_COT_EXT]
Code=reviews
Name=Reviews
Description=Reviews for Pages representing goods or other kinds of reviewable material.
Version=1.2.0
Date=2011-12-15
Author=Trustmaster
Copyright=
Notes=
SQL=
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=12345
Requires_modules=page
Requires_plugins=mcaptcha
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
rates=01:text:::Extended rating types, one title per line, up to 9 rating types
rows=02:string::15:Reviews displayed per page
order=03:select:ASC,DESC:DESC:Order (by date)
rpc=04:select:0,1,2,3:1:Ratings precision (digits after point)
ppc=05:select:0,1,2,3:2:Price precision (digits after point)
cur=06:string::EUR:Displayed currency
timeout=07:string::10:Edit timeout (minutes)
extra=08:text::extra1|Test extra:Extra fields, one per line, up to 5 entries, format: code|title
[END_COT_EXT_CONFIG]
==================== */

defined('COT_CODE') or die('Wrong URL.');

?>