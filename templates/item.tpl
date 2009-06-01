<div class="feed">
<div class="item" id="IITEM-{$iitem}">
<img src="{$channelFavicon}" width="16" height="16" class="icon" alt="" />
<span class="time">{$item.time}</span>
<span class="title" id="TITLE{$number}">{$item.title}</span>
<span class="source">
{if $conf.new_window == 'true'}
	<a href="{$item.url}" target="_blank">&raquo;
{else}
	<a href="{$item.url}">&raquo;
{/if}
{$item.name}
</a>
</span>
<div class="excerpt" id="ICONT{$number}">
{$item.body}
<div class="integration">
{foreach from=$sources key=source item=value}
	{include file="sources/$source.tpl"}
{/foreach}
</div></div></div></div>
