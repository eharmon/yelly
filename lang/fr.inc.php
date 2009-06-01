<?php
/* 
lylina news aggregator

Language: French Canadian
Author: Claude Gelinas (cgelinas@logixca.com)
Supports: 1.10,1.11,1.20,1.21

Copyright (C) 2006 Michael Wenzl
Copyright (C) 2006 Eric Harmon
Copyright (C) 2006 Claude Gelinas

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

$lang['NAME'] = 'French Canadian';

// Locale information (uses standard *nix locale codes)

$lang['LOCALE'] = 'fr_CA';

// Set the typical date format for this language

$lang['DATEFORMAT'] = '%A %B %e, %Y';

// It is recommended you leave the charset at UTF-8 for most
// languages, as this is pretty much universally accepted.

$lang['CHARSET'] = 'ISO-8859-1';


$lang['login'] = 'Authentification';
$lang['logout'] = 'fin de session';
$lang['preferences'] = 'pr&eacute;f&eacute;rences';
$lang['expand'] = 'agrandir';
$lang['collapse'] = 'diminuer';
$lang['4hours'] = '4 heures';
$lang['8hours'] = '8 heures';
$lang['16hours'] = '16 heures';
$lang['1day'] = '1 jour';
$lang['1week'] = '1 semaine';
$lang['default'] = 'standard';
$lang['powered'] = 'propuls&eacute; par';
$lang['newuser'] = 'Cr&eacute;er un nouvel utilisateur';
$lang['username'] = 'Nom d\'usager';
$lang['password'] = 'Mot de passe';
$lang['pwrepeat'] = 'R&eacute;p&eacute;tez votre mot de passe';
$lang['newuserbutton'] = 'Cr&eacute;er un utilisateur';
$lang['newfeed'] = 'Ajouter une source de nouvelles &agrave; votre page';
$lang['feedmain'] = 'Ajouter une nouvelle source de nouvelles &agrave; la page d\'accueil';
$lang['changepw'] = 'Changer votre mot de passe';
$lang['newpw'] = 'Nouveau mot de passe';
$lang['changepwbutton'] = 'Changer votre mot de passe';
$lang['recentfeeds'] = 'Sources de nouvelles fra&icirc;chement ajout&eacute;es';
$lang['user'] = 'Utilisateurs';
$lang['rmuser'] = 'Effacer l\'utilisateur';
$lang['rmconfirm'] = 'Vraiment effacer l\'utilisateur';
$lang['view'] = 'Consulter';
$lang['persfeeds'] = 'Vos sources';
$lang['mainfeeds'] = 'Principales sources';
$lang['subscribe'] = 'Abonnez-vous';
$lang['subfeed'] = 'Abonnez-vous &agrave; cette source';
$lang['optname'] = 'Nom de la source (optionnel)';
$lang['feedurl'] = 'URL du site web ou de la source RSS';
$lang['unsubconfirm'] = 'Vraiment vous d&eacute;sabonner?';
$lang['digg'] = 'soumettre &agrave; digg';
$lang['delicious'] = 'ajouter &agrave; del.icio.us';
$lang['reddit'] = 'soumettre &agrave; reddit';
$lang['furl'] = 'ajouter &agrave; furl';
$lang['sources'] = 'Sources';
$lang['search'] = 'Rechercher';
$lang['searchresults'] = 'Rechercher les r&eacute;sultats pour';
$lang['newsvine'] = 'essaimer aupr&egrave;s de newsvine';
$lang['lockdir'] = 'ERREUR: incapable de modifier le statut de \'lockfile\'. Veuillez, s\'il-vous-pla&icirc;t, v&eacute;rifier les permissions ou effacer puis recr&eacute;er ce fichier.';

// This most likely should never be changed.

define('MAGPIE_OUTPUT_ENCODING', $lang['CHARSET']);

?>
