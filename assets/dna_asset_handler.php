<?php
/*
Name: Thesis Developer Asset Handler
Author: Tim Milligan
Version: 1.3
Configuration:
Change $from (ln 56) to point to your update file, see update.php for example
Change the 'vzm' prefix on the class name (ln 12) and the dna_callout transient (ln 22 & 23 & 84) to your own
Rename the file to match your class name
*/

class dna_asset_handler {
	
	public function __construct() {
		if (is_dir(WP_CONTENT_DIR . '/thesis'))
			add_action('admin_init', array($this, 'get_all_updates'), 100);
	}
	
	public static function get_all_updates() {
		global $thesis;
		
		delete_transient('dna_callout'); //uncommenting this line will force an update check, for testing purposes only
		if (get_transient('dna_callout'))
			return;
		
		set_transient('dna_callout', time(), 60*60*24);


	


		$objects = array(
			'skins' 	=> thesis_skins::get_items(),
			'boxes' 	=> thesis_user_boxes::get_items()
		);


		
		// $objects = array(
		// 	'skins' 	=> thesis_skins::get_items(),
		// 	'boxes' 	=> thesis_user_boxes::get_items(),
		// 	'packages' 	=> thesis_user_packages::get_items()
		// );
		
		$transients = array(
			'skins' 	=> 'thesis_skins_update',
			'boxes' 	=> 'thesis_boxes_update'
		);
		
		$all = array();
		
		foreach ($objects as $object => $array)
			if (is_array($array) && !empty($array))
				foreach ($array as $class => $data)
					$all[$object][$class] = $data['version'];
		
		foreach ($transients as $key => $transient)
			if (get_transient($transient))
				unset($all[$key]);
		
		if (empty($all))
			return;
		
		$all['thesis'] = $thesis->version;
		
		$from = 'http://thesisdna.com/inject-dna/update.php';
		$post_args = array(
			'body' => array(
				'data' => serialize($all),
				'wp' => $GLOBALS['wp_version'],
				'php' => phpversion(),
				'user-agent' => "WordPress/{$GLOBALS['wp_version']};" . home_url()
			)
		);
		
		$post = wp_remote_post($from, $post_args);

		if (is_wp_error($post) || empty($post['body']))
			return;
		
		$returned = @unserialize($post['body']);

		if (!is_array($returned))
			return;

		foreach ($returned as $type => $data) // will only return the data that we need to update
			if (in_array("thesis_{$type}_update", $transients))
				set_transient("thesis_{$type}_update", $returned[$type], 60*60*24);
	}
	
	public function reset_transients() {
		// foreach (array('skins', 'boxes', 'packages') as $tr)
		foreach (array('skins', 'boxes') as $tr)
			delete_transient("thesis_{$tr}_update");
		delete_transient('dna_callout');
		wp_cache_flush();
	}
}
?>