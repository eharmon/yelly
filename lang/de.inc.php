<?php
/* 
lylina news aggregator

Language: German (Deutsch)
Authors: Michael Wenzl (michael.wenzl@lxconsult.de)
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

$lang['NAME'] = 'German (Deutsch)';

// Locale information (uses standard *nix locale codes)
// The name of the locale may differ, e.g. on debian based hosts
// the german UTF-8 locale is named de_DE.utf8, while others
// call it de_DE.UTF-8 or de_DE.UTF8
// German readers may find this discussion on s9y and locales usefull:
// http://micha.monsteradmin.de/index.php?/archives/54-Nochmal-Serendipity-und-Umlaute.html

$lang['LOCALE'] = array('de_DE.UTF8','de_DE.utf8@euro','de_DE@euro','de_DE');

// Set the typical date format for this language

$lang['DATEFORMAT'] = '%A, %d. %B %Y';

// It is recommended you leave the charset at UTF-8 for most
// languages, as this is pretty much universally accepted.h, 

$lang['CHARSET'] = 'UTF-8';

$lang['login'] = 'Login';
$lang['logout'] = 'Logout';
$lang['preferences'] = 'Einstellungen';
$lang['expand'] = 'Erweiterte Ansicht';
$lang['collapse'] = 'Nur Titel anzeigen';
$lang['4hours'] = '4 Stunden';
$lang['8hours'] = '8 Stunden';
$lang['16hours'] = '16 Stunden';
$lang['1day'] = '1 Tag';
$lang['1week'] = '1 Woche';
$lang['default'] = 'default';
$lang['powered'] = 'powered by';
$lang['newuser'] = 'Neuen Benutzer anlegen';
$lang['username'] = 'Benutzername';
$lang['password'] = 'Passwort';
$lang['pwrepeat'] = 'Passwort wiederholen';
$lang['newuserbutton'] = 'Benutzer anlegen';
$lang['newfeed'] = 'Neuer Feed f&uuml;r die Benutzerseite';
$lang['feedmain'] = 'Neuer Feed f&uuml;r die Hauptseite';
$lang['changepw'] = 'Passwort &auml;ndern';
$lang['newpw'] = 'Neues Passwort';
$lang['changepwbutton'] = 'Passwort speichern';
$lang['recentfeeds'] = 'Neueste RSS-Feeds';
$lang['user'] = 'Benutzer';
$lang['rmuser'] = 'Diesen Benutzer l&ouml;schen';
$lang['rmconfirm'] = 'Diesen Benutzer wirklich l&ouml;schen:';
$lang['view'] = 'Ansicht';
$lang['persfeeds'] = 'Pers&ouml;nliche Feeds';
$lang['mainfeeds'] = 'Hauptseite';
$lang['subscribe'] = 'Abonnieren';
$lang['subfeed'] = 'Diesen Feed abonnieren';
$lang['optname'] = 'Name des Feeds (optional)';
$lang['feedurl'] = 'URL des Feeds';
$lang['unsubconfirm'] = 'Abonnement wirklich l&ouml;schen?';
$lang['digg'] = 'zu digg &uuml;bertragen';
$lang['delicious'] = 'bei del.icio.us hinzuf&uuml;gen';
$lang['reddit'] = 'zu reddit &uuml;bertragen';
$lang['furl'] = 'bei furl hinzuf&uuml;gen';
$lang['sources'] = 'Quellen';
$lang['search'] = 'Suche';
$lang['searchresults'] = 'Suchergebnisse f&uuml;r';
$lang['newsvine'] = 'bei newsvine eintragen';
$lang['lockdir'] = 'Fehler: Auf die Datei \'lockfile\' kann nicht zugegriffen werden. Bitte prüfen Sie die Zugriffsrechte der Datei. Sollten der Fehler weiterhin bestehen, löschen Sie die Datei und erstellen Sie sie neu.';

// This most likely should never be changed.

define('MAGPIE_OUTPUT_ENCODING', $lang['CHARSET']);

?>