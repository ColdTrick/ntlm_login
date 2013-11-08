<?php

	$plugin = elgg_extract("entity", $vars);
	$user = elgg_get_page_owner_entity();
	
	$ntlm_auth_hashes = $user->ntlm_auth_hash;
	if (!empty($ntlm_auth_hashes)) {
		echo elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:usersettings:connected:description")));
		
		if (!is_array($ntlm_auth_hashes)) {
			$ntlm_auth_hashes = array($ntlm_auth_hashes);
		}
		
		echo "<table class='elgg-table mbm'>";
		
		echo "<tr>";
		echo "<th>" . elgg_echo("ntlm_login:settings:organisation:domain") . "</th>";
		echo "<th>" . elgg_echo("ntlm_login:admin:verify:ntlm:username") . "</th>";
		echo "<th>" . elgg_echo("remove") . "</th>";
		echo "</tr>";
		
		foreach ($ntlm_auth_hashes as $ntlm_auth_hash) {
			$ntlm_combo = $plugin->getUserSetting($ntlm_auth_hash, $user->getGUID());
			list($ntlm_domain, $ntlm_username) = explode("/", $ntlm_combo);
			
			$remove_link = elgg_view("output/confirmlink", array(
				"text" => elgg_echo("remove"),
				"confirm" => elgg_echo("deleteconfirm"),
				"href" => "action/ntlm_login/unlink?user_guid=" . $user->getGUID() . "&hash=" . $ntlm_auth_hash
			));
			
			echo "<tr>";
			echo "<td>" . $ntlm_domain . "</td>";
			echo "<td>" . $ntlm_username . "</td>";
			echo "<td>" . $remove_link . "</td>";
			echo "</tr>";
		}
		
		echo "</table>";
	} else {
		echo elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:usersettings:not_connected:description")));
	}