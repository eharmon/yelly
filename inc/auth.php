<?php
/* 
lylina news aggregator

Copyright (C) 2005 Andreas Gohr
Copyright (C) 2006-2007 Eric Harmon

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

function login($user,$pass) {
	$sql = "SELECT pass, id
                  FROM lylina_users
                 WHERE login = '".addslashes($user)."'";
	$result = runSQL($sql);
	if(count($result) != 1 || !auth_verifyPassword($pass,$result[0]['pass']))
		return 0;
	setAuthToken($result[0]['id']);
	return $result[0]['id'];
}

function logout() {
	setCookie('lylina_auth','',time()+60*60*24*365);
	setCookie('lylina_uid','',time()+60*60*24*365);
	return 0;
}

function checkAuthToken() {
	$uid = $_COOKIE['lylina_uid'];
	$rnd = $_COOKIE['lylina_auth'];

	if(!$rnd || !$uid) return 0;

	$sql = "SELECT magic
                 FROM lylina_users
                WHERE id = '".addslashes($uid)."'";
	$result = runSQL($sql);
	if($result[0]['magic'] == $rnd){
		setAuthToken($uid);
		return $uid;
	}
	return 0;
}

function checkAuth($request) {
	if($request['logout'])
		$UID = logout();
	elseif($request['u'])
		$UID = login($request['u'],$request['p']);
	else
		$UID = checkAuthToken();
	return $UID;
}

function setAuthToken($uid) {
	$rnd = md5(uniqid(rand(),true));
	$sql = "UPDATE lylina_users
                   SET magic = '$rnd'
                 WHERE id = $uid";
	runSQL($sql);
	setCookie('lylina_auth',$rnd,time()+60*60*24*365);
	setCookie('lylina_uid',$uid,time()+60*60*24*365);
}


function addUser($user,$pass) {
	global $conf;

	$sql = "INSERT INTO lylina_users
                  SET login = '".addslashes($user)."',
                      pass = '".addslashes(auth_cryptPassword($pass))."'";
	runSQL($sql);
}

function setPassword($uid,$pass) {
	global $conf;

	$sql = "UPDATE lylina_users
                   SET pass = '".addslashes(auth_cryptPassword($pass))."'
                 WHERE id = $uid";
	runSQL($sql);
}


/**
 * Encrypts a password using the given method and salt
 *
 * If the selected method needs a salt and none was given, a random one
 * is chosen.
 *
 * The following methods are understood:
 *
 *   smd5  - Salted MD5 hashing
 *   md5   - Simple MD5 hashing
 *   sha1  - SHA1 hashing
 *   ssha  - Salted SHA1 hashing
 *   crypt - Unix crypt
 *   mysql - MySQL password (old method)
 *   my411 - MySQL 4.1.1 password
 *
 * @author  Andreas Gohr <andi@splitbrain.org>
 * @return  string  The crypted password
 */
function auth_cryptPassword($clear,$method='',$salt='') {
	global $conf;
	if(empty($method)) $method = $conf['passcrypt'];

  // prepare a salt
	if(empty($salt)) $salt = md5(uniqid(rand(), true));

	switch(strtolower($method)) {
		case 'smd5':
			return crypt($clear,'$1$'.substr($salt,0,8).'$');
		case 'md5':
			return md5($clear);
		case 'sha1':
			return sha1($clear);
		case 'ssha':
			$salt=substr($salt,0,4);
			return '{SSHA}'.base64_encode(pack("H*", sha1($clear.$salt)).$salt);
		case 'crypt':
			return crypt($clear,substr($salt,0,2));
		case 'mysql':
      			// from http://www.php.net/mysql comment by <soren at byu dot edu>
			$nr = 0x50305735;
			$nr2 = 0x12345671;
			$add = 7;
			$charArr = preg_split("//", $clear);
			foreach ($charArr as $char) {
				if (($char == '') || ($char == ' ') || ($char == '\t')) continue;
				$charVal = ord($char);
				$nr ^= ((($nr & 63) + $add) * $charVal) + ($nr << 8);
				$nr2 += ($nr2 << 8) ^ $nr;
				$add += $charVal;
			}
			return sprintf("%08x%08x", ($nr & 0x7fffffff), ($nr2 & 0x7fffffff));
		case 'my411':
			return '*'.sha1(pack("H*", sha1($clear)));
		default:
			msg("Unsupported crypt method $method",-1);
	}
}

/**
 * Verifies a cleartext password against a crypted hash
 *
 * The method and salt used for the crypted hash is determined automatically
 * then the clear text password is crypted using the same method. If both hashs
 * match true is is returned else false
 *
 * @author  Andreas Gohr <andi@splitbrain.org>
 * @return  bool
 */
function auth_verifyPassword($clear,$crypt) {
	$method='';
	$salt='';

	// determine the used method and salt
	$len = strlen($crypt);
	if(substr($crypt,0,3) == '$1$') {
		$method = 'smd5';
		$salt   = substr($crypt,3,8);
	} elseif(substr($crypt,0,6) == '{SSHA}') {
		$method = 'ssha';
		$salt   = substr(base64_decode(substr($crypt, 6)),20);
	} elseif($len == 32) {
		$method = 'md5';
	} elseif($len == 40) {
		$method = 'sha1';
	} elseif($len == 16) {
		$method = 'mysql';
	} elseif($len == 41 && $crypt[0] == '*') {
		$method = 'my411';
	} else {
		$method = 'crypt';
		$salt   = substr($crypt,0,2);
	}

	// crypt and compare
	if(auth_cryptPassword($clear,$method,$salt) === $crypt) {
		return true;
	}
	return false;
}

function isAdmin($UID) {
	if($UID == 1)
		return true;
	else
		return false;
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
?>
