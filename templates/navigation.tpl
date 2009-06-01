<!--<div id="navigation">
<a href="index.php"><img src="{$conf.page_logo}" alt="{$conf.page_title}" /></a>
<img src="img/spacer.gif" width="10" height="1" alt="" class="spacer" />
<img src="img/calendar-trans.png" alt="" /> 
<a href="index.php?hours=4">{$lang.4hours}</a>
<a href="index.php?hours=8">{$lang.8hours}</a>
<a href="index.php?hours=16">{$lang.16hours}</a>
<a href="index.php?hours=24">{$lang.1day}</a>
<a href="index.php?hours=168">{$lang.1week}</a>
{if $user}
	<img src="img/spacer.gif" width="50" height="1" alt="" class="spacer" />
	<img src="img/preferences-trans.png" alt="" />
	<a href="edit.php">{$lang.preferences}</a>
	<img src="img/spacer.gif" width="50" height="1" alt="" class="spacer" />
	<a href="index.php?logout=1">{$lang.logout}</a>
{/if}
<img src="img/spacer.gif" width="50" height="1" alt="" class="spacer" />
{if !$user}
	<form method="post" action="index.php" class="login">
	<img src="img/users-trans.png" alt="user" /> <input type="text" name="u" />
	<img src="img/password-trans.png" alt="password" /> <input type="password" name="p" />
	<input type="submit" value="{$lang.login}" />
	</form>
{/if}
</div>-->

<div id="navigation"><img src="img/mini.png" width="39" height="25" alt="lylina" id="logo" /> <img src="img/div.png" 
width="1" height="20" /><div id="message"><img src="img/4-1.gif" />Please wait while lylina updates...</div>
{if !$user}
<div id="login">
	<form method="post" action="index.php" class="login">
	<img src="img/users-trans.png" alt="user" /> <input type="text" name="u" />
	<img src="img/password-trans.png" alt="password" /> <input type="password" name="p" />
	<input type="submit" value="Login" />
	</form>
</div>
{/if}
</div>
