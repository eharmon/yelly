<?php
/* 
lylina news aggregator

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

print '<html><head><title>lylina auto-upgrade system</title></head><body>';
print 'lylina is starting the upgrade process...<br />';

require_once('installer/common.php');
verify_upgrade($upgrade_bit);
require_once('installer/upgrade/upgrade.php');

print 'Done! <a href="index.php">Click here to continue</a>';

print '</body></html>';

?>