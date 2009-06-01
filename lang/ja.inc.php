<?php
/* 
lylina news aggregator

Language: Japanese
Authors: Tadashi Jokagi (elf2000@users.sourceforge.net)
Supports: 1.10,1.11,1.20,1.21

Copyright (C) 2006 Michael Wenzl
Copyright (C) 2006 Eric Harmon
Copyright (C) 2006 Tadashi Jokagi

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

$lang['NAME'] = 'Japanese';

// Locale information (uses standard *nix locale codes)

$lang['LOCALE'] = 'ja_JP.UTF-8';

// Set the typical date format for this language

$lang['DATEFORMAT'] = '%Y-%m-%d(%a)';

// It is recommended you leave the charset at UTF-8 for most
// languages, as this is pretty much universally accepted.

$lang['CHARSET'] = 'UTF-8';

$lang['login'] = 'ログイン';
$lang['logout'] = 'ログアウト';
$lang['preferences'] = 'プリファレンス';
$lang['expand'] = '開く';
$lang['collapse'] = '閉じる';
$lang['4hours'] = '4 時間前';
$lang['8hours'] = '8 時間前';
$lang['16hours'] = '16 時間前';
$lang['1day'] = '1 日前';
$lang['1week'] = '1 週間前';
$lang['default'] = 'デフォルト';
$lang['powered'] = 'powered by';
$lang['newuser'] = '新規ユーザーの作成';
$lang['username'] = 'ユーザー名';
$lang['password'] = 'パスワード';
$lang['pwrepeat'] = 'パスワード(確認)';
$lang['newuserbutton'] = 'ユーザーを作成する';
$lang['newfeed'] = '自分のページにフィードの追加';
$lang['feedmain'] = 'メインページにフィードの追加';
$lang['changepw'] = 'パスワードの変更';
$lang['newpw'] = '新規パスワード';
$lang['changepwbutton'] = 'パスワードを変更する';
$lang['recentfeeds'] = '最近追加されたフィード一覧';
$lang['user'] = 'ユーザー一覧';
$lang['rmuser'] = 'ユーザー削除';
$lang['rmconfirm'] = '本当にユーザーを削除しますか';
$lang['view'] = '閲覧する';
$lang['persfeeds'] = '自分のフィード';
$lang['mainfeeds'] = 'メインフィード';
$lang['subscribe'] = '購読する';
$lang['subfeed'] = 'このフィードを購読する';
$lang['optname'] = 'フィードの名前 (オプション)';
$lang['feedurl'] = 'フィードの URL';
$lang['unsubconfirm'] = '本当に購読を止めますか?';
$lang['digg'] = 'digg に投稿する';
$lang['delicious'] = 'del.icio.us に追加する';
$lang['reddit'] = 'reddit に投稿する';
$lang['furl'] = 'furl に追加する';
$lang['sources'] = 'ソース';
$lang['search'] = '検索';
$lang['newsvine'] = 'newsvine の種';
$lang['lockdir'] = 'ERROR: unable to change status of \'lockfile\'. Please check permissions or delete and recreate the file.';

// This most likely should never be changed.

define('MAGPIE_OUTPUT_ENCODING', $lang['CHARSET']);

?>
