<?php
/* 
lylina news aggregator

Language: English
Authors: Eric Harmon
Supports: 1.10,1.11,1.20,1.21

Copyright (C) 2006 Michael Wenzl
Copyright (C) 2006 Eric Harmon

lylina is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

lylina is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with lylina; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

$lang['NAME'] = 'English';

// Locale information (uses standard *nix locale codes)

$lang['LOCALE'] = 'en_US';

// Set the typical date format for this language

$lang['DATEFORMAT'] = '%A %B %e, %Y';

// It is recommended you leave the charset at UTF-8 for most
// languages, as this is pretty much universally accepted.

$lang['CHARSET'] = 'UTF-8';


$lang['login'] = 'Login';
$lang['logout'] = 'logout';
$lang['preferences'] = 'preferences';
$lang['expand'] = 'expand';
$lang['collapse'] = 'collapse';
$lang['4hours'] = '4 hours';
$lang['8hours'] = '8 hours';
$lang['16hours'] = '16 hours';
$lang['1day'] = '1 day';
$lang['1week'] = '1 week';
$lang['default'] = 'default';
$lang['powered'] = 'powered by';
$lang['newuser'] = 'Create new user';
$lang['username'] = 'Username';
$lang['password'] = 'Password';
$lang['pwrepeat'] = 'Repeat password';
$lang['newuserbutton'] = 'Create User';
$lang['newfeed'] = 'Add new feed to your page';
$lang['feedmain'] = 'Add new feed to main page';
$lang['changepw'] = 'Change your password';
$lang['newpw'] = 'New Password';
$lang['changepwbutton'] = 'Change Password';
$lang['recentfeeds'] = 'Newly added feeds';
$lang['user'] = 'Users';
$lang['rmuser'] = 'Delete user';
$lang['rmconfirm'] = 'Really delete user';
$lang['view'] = 'View';
$lang['persfeeds'] = 'Your Feeds';
$lang['mainfeeds'] = 'Main feeds';
$lang['subscribe'] = 'Subscribe';
$lang['subfeed'] = 'Subscribe to this feed';
$lang['optname'] = 'Name of feed (optional)';
$lang['feedurl'] = 'URL of website or feed';
$lang['unsubconfirm'] = 'Really unsubscribe?';
$lang['digg'] = 'submit to digg';
$lang['delicious'] = 'add to del.icio.us';
$lang['reddit'] = 'submit to reddit';
$lang['furl'] = 'add to furl';
$lang['sources'] = 'Sources';
$lang['search'] = 'Search';
$lang['searchresults'] = 'Search results for';
$lang['newsvine'] = 'seed at newsvine';
$lang['lockdir'] = 'ERROR: unable to change status of \'lockfile\'. Please check permissions or delete and recreate the file.';

// This most likely should never be changed.

define('MAGPIE_OUTPUT_ENCODING', $lang['CHARSET']);

?>
