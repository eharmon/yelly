<?php
class SimplePie_Cache_Extras extends SimplePie
{
	function get_cache_object()
	{
//		$cache =& new $this->cache_class($this->cache_location, 
//call_user_func($this->cache_name_function, $this->feed_url), 'spc');
//		return $cache;

		$cache =& SimplePie_Cache::create($this->cache_location, call_user_func($this->cache_name_function, $this->feed_url), 'spc');
		return $cache;

	}
 
	function get_cache_filename()
	{
		$cache = $this->get_cache_object();
		return $cache->name;
	}
 
	function get_cache_timestamp()
	{
		$cache = $this->get_cache_object();
		return $cache->mtime();
	}
 
	function get_cache_time_remaining($format = false)
	{
		$cache = $this->get_cache_object();
		$remaining = ($cache->mtime() + $this->cache_duration) - 
time();
 
		if ($format)
		{
			return SimplePie_Misc::time_hms($remaining);
		}
		else
		{
			return $remaining;
		}
	}
}
?>


