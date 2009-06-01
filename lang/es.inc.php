<?php
/* 
lylina news aggregator

Language: Spanish (Español)
Authors: Gonzalo Esteban
Supports: 1.10,1.11,1.20,1.21

Copyright (C) 2006 Michael Wenzl
Copyright (C) 2006 Eric Harmon
Copyright (C) 2006 Gonzalo Esteban

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

$lang['NAME'] = 'Spanish (Español)';

// Locale information (uses standard *nix locale codes)

$lang['LOCALE'] = array('es','es_es','es_ES');

// Set the typical date format for this language

$lang['DATEFORMAT'] = '%A, %d de %B de %Y';

// It is recommended you leave the charset at UTF-8 for most
// languages, as this is pretty much universally accepted.

$lang['CHARSET'] = 'UTF-8';


$lang['login'] = 'Inciar sesión';
$lang['logout'] = 'Cerrar sesión';
$lang['preferences'] = 'preferencias';
$lang['expand'] = 'expandir';
$lang['collapse'] = 'contraer';
$lang['4hours'] = '4 horas';
$lang['8hours'] = '8 horas';
$lang['16hours'] = '16 horas';
$lang['1day'] = '1 día';
$lang['1week'] = '1 semana';
$lang['default'] = 'por defecto';
$lang['powered'] = 'motorizado por';
$lang['newuser'] = 'Crear nuevo usuario';
$lang['username'] = 'Usuario';
$lang['password'] = 'Contraseña';
$lang['pwrepeat'] = 'Repita contraseña';
$lang['newuserbutton'] = 'Crear usuario';
$lang['newfeed'] = 'Añdir nuevo contenido personal';
$lang['feedmain'] = 'Añadir nuevo contenido principal';
$lang['changepw'] = 'Cambiar su contraseña';
$lang['newpw'] = 'Nueva contraseña';
$lang['changepwbutton'] = 'Cambiar contraseña';
$lang['recentfeeds'] = 'Contenidos recientemente añadidos';
$lang['user'] = 'Usuarios';
$lang['rmuser'] = 'Borrar usuarios';
$lang['rmconfirm'] = 'Borrar realmente el usuario';
$lang['view'] = 'Vista';
$lang['persfeeds'] = 'Sus contenidos';
$lang['mainfeeds'] = 'Contenidos principales';
$lang['subscribe'] = 'Suscribir';
$lang['subfeed'] = 'Subscribir a este contenido';
$lang['optname'] = 'Nombre del contenido (opcional)';
$lang['feedurl'] = 'URL del sito web o contenido';
$lang['unsubconfirm'] = '¿Cancelar realmente la suscripción';
$lang['digg'] = 'enviar a digg';
$lang['delicious'] = 'añadir a del.icio.us';
$lang['reddit'] = 'enviar a reddit';
$lang['furl'] = 'añadir a furl';
$lang['sources'] = 'Fuentes';
$lang['search'] = 'Buscar';
$lang['searchresults'] = 'Buscar resultados para';
$lang['newsvine'] = 'sembrar en newsvine';
$lang['lockdir'] = 'ERROR: imposible cambiar el estado de \'fichero bloqueado\'. Por favor, compruebe los permisos o borre y cree de nuevo el fichero.';

// This most likely should never be changed.

define('MAGPIE_OUTPUT_ENCODING', $lang['CHARSET']);

?>
