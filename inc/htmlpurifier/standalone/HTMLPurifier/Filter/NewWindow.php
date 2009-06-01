<?php

class HTMLPurifier_Filter_NewWindow extends HTMLPurifier_Filter {
	
	public $name = 'NewWindow';

	public function postFilter($html, $config, $context) {
		$post_regex = '/<a href([^>]+)>/i';
		$post_replace = '<a href\\1 target="_blank">';
		return preg_replace($post_regex, $post_replace, $html);
	}
}

?>
