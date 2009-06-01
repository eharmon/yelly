<?php
/* 
libFeedFinder

Copyright (C) 2006-2007 Eric Harmon
Portions Copyright (C) 2006 Andrew Smith

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

class feedFinder {
	var $url;
	var $parsed;
	var $feeds = array();

	function feedFinder($url,$doSyndic8=false,$debug=false) {
		@ini_set('user_agent','libFeedFinder/0.1 (PHP/' . phpversion() . ')');
		
		if($debug)echo 'libFeedFinder/0.1 (PHP/' . phpversion() . ")<br />\n";
		
		$this->url = $url;

		// Grab page content
		$page = file_get_contents($this->url);

		$this->parsed = parse_url($this->url);
		
		// ------ STAGE 0: Is Feed? ------
		
		// If we were given a valid feed, just return it
		if($this->valid_feed($url)) {
			$this->push_feed($url);
			return $this->feeds;
		}

		// ------ STAGE 1: Link Tags ------

		// Match all link tags, tell me their href's
		preg_match_all('/<\s*link.+href="?([^"\s]+)"?.*>/i', $page, $matches);

		$guesses = $matches[1];

		if($debug) { echo "<pre>"; print_r($guesses); echo "</pre>"; }

		// If match found
		if($guesses) {
			foreach($guesses as $guess) {
				if($debug) echo "(link) Trying $guess<br />\n";
				if($this->valid_feed($guess)) {
					if($debug) echo "Got one! Adding " . $this->make_full_path($guess) . "<br />\n";
					$this->push_feed($guess);
				}
			}
		}
		if(count($this->feeds))
			return $this->feeds;

		// ------ STAGE 2: Local a Tags ------

		// Match all a tags, tell me their href's
		preg_match_all('/<\s*a.+href="?([^"\s]+)"?.*>/i', $page, $matches);

		$guesses = $matches[1];

		// If match found
		if($guesses) {
			foreach($guesses as $guess) {
				// If URL contains anything that looks like a feed, and is on this site
				if(stristr($guess,$this->parsed['host']) && (stristr($guess,"rss") || stristr($guess,"rdf") || stristr($guess,"xml") || stristr($guess,"atom"))) {
					if($debug) echo "(a local) Trying $guess<br />\n";
					if($this->valid_feed($guess)) {
						if($debug) echo "Got one! Adding " . $this->make_full_path($guess) . "<br />\n";
						$this->push_feed($guess);
					}
				}
			}
		}
		if(count($this->feeds))
			return $this->feeds;

		// ------ STAGE 3: All a Tags ------

		// If match found
		if($guesses) {
			foreach($guesses as $guess) {
				// If URL contains anything that looks like a feed, and is anywhere
				if(stristr($guess,"rss") || stristr($guess,"rdf") || stristr($guess,"xml") || stristr($guess,"atom") || stristr($guess,"feedburner")) {
					if($debug) echo "(a all) Trying $guess<br />\n";
					if($this->valid_feed($guess)) {
						if($debug) echo "Got one! Adding " . $this->make_full_path($guess) . "<br />\n";
						$this->push_feed($guess);
					}
				}
			}
		}

		if(count($this->feeds))
			return $this->feeds;
			
		// ------ STAGE 4: URL Guess ------

		$files = array('atom.xml', 'index.rdf', 'rss.xml', 'index.xml', 'wiki/index.php?title=Special:Recentchanges&feed=rss');
		foreach($files as $file) {
			$guess = $this->parsed['scheme'] . '://' . $this->parsed['host'] . '/' . $file;
			if($debug) echo "(guess) Trying $guess<br />\n";
			if($this->valid_feed($guess)) {
				if($debug) echo "Got one! Adding " . $this->make_full_path($guess) . "<br />\n";
				$this->push_feed($guess);
			}
		}
		
		if(count($this->feeds))
			return $this->feeds;

		// ------ STAGE 5: Syndic8 Query (Optional) ------
		if($doSyndic8) {
			$syndic8 = @file_get_contents('http://www.syndic8.com/feedlist.php?Stage=1&FormShowStatusCheck=on&FormShowStatus=Syndicated&FormShowMatch=' . $this->parsed['host']);

			// Match all a tags, tell me their href's
			preg_match_all('/<\s*a.+href="?([^"\s]+)"?.*>/i', $syndic8, $matches);

			$guesses = $matches[1];
		
			// If match found
			if($guesses) {
				foreach($guesses as $guess) {
					// If URL contains anything that looks like a feed, and is anywhere
					if(stristr($guess,"rss") || stristr($guess,"rdf") || stristr($guess,"xml") || stristr($guess,"atom") || stristr($guess,"feedburner")) {
						if($debug) echo "(syndic8) Trying $guess<br />\n";
						if($this->valid_feed($guess)) {
							if($debug) echo "Got one! Adding " . $this->make_full_path($guess) . "<br />\n";
							$this->push_feed($guess);
						}
					}
				}
			}
		}
		return $this->feeds;
	}

	// Check if a feed is valid
	function valid_feed($guess) {
		if($debug) $temp = $guess;
		$guess = $this->make_full_path($guess);

		if($debug) echo "Checking for validity of $temp, fullpath $guess<br />\n";

		// Grab feed content
		$feed = @file_get_contents($guess);

		// Find any indication that it's valid
		if(stristr($feed,"<rss") || stristr($feed,"<rdf") || stristr($feed,"<feed"))
			return true;
		else
			return false;
	}

	// Tries to reform a fullpath URL
	function make_full_path($url) {
		if(substr($url,0,4) != 'http') {
			// If it starts in a slash, we just need to append the hostname
			if(substr($url,0,1) == '/')
				$url = $this->parsed['scheme'] . '://' . $this->parsed['host'] . $url;
			// If it doesn't, we need to apend the directory this URL is located at
			else {
				$path = pathinfo($this->parsed['path']);
				$url = $this->parsed['scheme'] . '://' . $this->parsed['host'] . $path['dirname'] . '/' . $url;
			}
		}
		return $url;
	}

	// Push a feed URL onto feed array, adding full path if needed
	function push_feed($child) {
		// If it's not a full path
		$child = $this->make_full_path($child);
		if($debug) echo "Pushing $child onto feeds<br />\n";
		array_push($this->feeds,$child);
	}
}
?>
