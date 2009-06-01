<?php
/* 
lylina news aggregator

Copyright (C) 2006 Eric Harmon

Upgrade Script: 1.xx -> 1.20

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

verify_upgrade($upgrade_bit);

print 'Please wait while the installer upgrades lylina (1.xx->1.21)...<br />';

die('I\'m sorry, the lylina upgrader requires lylina v1.21 or higher. Please see \'UPGRADE\' to upgrade lylina to v1.21 before continuing.');

?>