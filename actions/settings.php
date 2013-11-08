<?php
/**
 * Saves plugin settings.
 *
 * @uses array $_REQUEST["params"]    A set of key/value pairs to save to the ElggPlugin entity
 * @uses int   $_REQUEST["plugin_id"] The ID of the plugin
 *
 * @package Elgg.Core
 * @subpackage Plugins.Settings
 */

$params = get_input("params");
$organisations = get_input("organisations");
$plugin_id = get_input("plugin_id");
$plugin = elgg_get_plugin_from_id($plugin_id);

if (!($plugin instanceof ElggPlugin)) {
	register_error(elgg_echo("plugins:settings:save:fail", array($plugin_id)));
	forward(REFERER);
}

$plugin_name = $plugin->getManifest()->getName();

// save the organisation configurations
$valid_organisations = array();
if (!empty($organisations)) {

	foreach ($organisations["name"] as $index => $value) {
		$name = $value;
		$domain = $organisations["domain"][$index];
		$ip = $organisations["ip"][$index];

		if (empty($name) && empty($domain) && empty($ip)) {
			// empty sub-form (or template)
			continue;
		} elseif (empty($name) || empty($domain) || empty($ip)) {
			// one of the required fields is empty
			register_error(elgg_echo("ntlm_login:action:settings:organisation", array($name, $domain, $ip)));
			forward(REFERER);
		} else {
			$valid_organisations[] = array(
				"name" => $name,
				"domain" => $domain,
				"ip" => $ip
			);
		}

	}
}

if (!empty($valid_organisations)) {
	$plugin->set("organisations", json_encode($valid_organisations));
} else {
	$plugin->unsetSetting("organisations");
}


$result = false;

// save the default setings
foreach ($params as $k => $v) {
	$result = $plugin->setSetting($k, $v);
	if (!$result) {
		register_error(elgg_echo("plugins:settings:save:fail", array($plugin_name)));
		forward(REFERER);
		exit;
	}
}

system_message(elgg_echo("plugins:settings:save:ok", array($plugin_name)));
forward(REFERER);