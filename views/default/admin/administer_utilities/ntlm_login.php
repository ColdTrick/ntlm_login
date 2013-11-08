<?php

	$tabs = array(
		array(
			"name" => "settings",
			"text" => elgg_echo("settings"),
			"href" => "admin/plugin_settings/ntlm_login",
		),
		array(
			"name" => "verify",
			"text" => elgg_echo("ntlm_login:admin:verify"),
			"href" => "admin/administer_utilities/ntlm_login",
			"selected" => true
		)
	);
	
	echo elgg_view("navigation/tabs", array("tabs" => $tabs));
	
	$continue = true;
	$client_ip = ntlm_login_get_client_ip();
	
	// check if we can get the local user
	$ntlm_auth = ntlm_login_get_local_user();
	$title = elgg_echo("ntlm_login:admin:verify:ntlm");
	
	if (!empty($ntlm_auth)) {
		$content = elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:admin:verify:ntlm:description"), "class" => "elgg-message elgg-state-success pbn", "style" => "cursor:default;"));
		
		$content .= "<table class='elgg-table'>";
		$content .= "<tr>";
		$content .= "<th>" . elgg_echo("ntlm_login:admin:verify:ntlm:name") . "</th>";
		$content .= "<th>" . elgg_echo("ntlm_login:admin:verify:ntlm:value") . "</th>";
		$content .= "</tr>";
		
		$content .= "<tr>";
		$content .= "<td>" . elgg_echo("ntlm_login:admin:verify:ntlm:username") . "</td>";
		$content .= "<td>" . elgg_extract("username", $ntlm_auth) . "</td>";
		$content .= "</tr>";
		
		$content .= "<tr>";
		$content .= "<td>" . elgg_echo("ntlm_login:settings:organisation:domain") . "</td>";
		$content .= "<td>" . elgg_extract("domain", $ntlm_auth) . "</td>";
		$content .= "</tr>";
		
		$content .= "<tr>";
		$content .= "<td>" . elgg_echo("ntlm_login:admin:verify:ntlm:workstation") . "</td>";
		$content .= "<td>" . elgg_extract("workstation", $ntlm_auth) . "</td>";
		$content .= "</tr>";
		
		$content .= "<tr>";
		$content .= "<td>" . elgg_echo("ntlm_login:admin:verify:ntlm:ip") . "</td>";
		$content .= "<td>" . $client_ip . "</td>";
		$content .= "</tr>";
		
		$content .= "</table>";
	} else {
		$continue = false;
		
		$content = elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:admin:verify:ntlm:fail"), "class" => "elgg-message elgg-state-error pbn", "style" => "cursor:default;"));
	}
	
	echo elgg_view_module("inline", $title, $content);
	
	// can we find match an organisation to the ip-address
	if ($continue) {
		$title = elgg_echo("ntlm_login:admin:verify:organisation");
		
		$organisations = ntlm_login_get_organisations_by_ip($client_ip);
		
		if (!empty($organisations)) {
			$content = elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:admin:verify:organisation:description", array($client_ip)), "class" => "elgg-message elgg-state-success pbn", "style" => "cursor:default;"));
			
			$content .= "<table class='elgg-table'>";
			$content .= "<tr>";
			$content .= "<th>" . elgg_echo("ntlm_login:settings:organisation:name") . "</th>";
			$content .= "<th>" . elgg_echo("ntlm_login:settings:organisation:domain") . "</th>";
			$content .= "<th>" . elgg_echo("ntlm_login:settings:organisation:ip") . "</th>";
			$content .= "</tr>";
			
			foreach ($organisations as $organisation) {
				$content .= "<tr>";
				$content .= "<td>" . elgg_view("output/text", array("value" => $organisation["name"])) . "</td>";
				$content .= "<td>" . elgg_view("output/text", array("value" => $organisation["domain"])) . "</td>";
				$content .= "<td>" . elgg_view("output/longtext", array("value" => $organisation["ip"])) . "</td>";
				$content .= "</tr>";
			}
			
			$content .= "</table>";
		} else {
			$continue = false;
			
			$content = elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:admin:verify:organisation:fail", array($client_ip)), "class" => "elgg-message elgg-state-error pbn", "style" => "cursor:default;"));
		}
		
		echo elgg_view_module("inline", $title, $content);
	}
	
	// validate the organisations to the user
	if ($continue) {
		$title = elgg_echo("ntlm_login:admin:verify:match");
		
		$matched = ntlm_login_match_user_to_organisation($ntlm_auth, $organisations);
		if (!empty($matched)) {
			$content = elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:admin:verify:match:description"), "class" => "elgg-message elgg-state-success pbn", "style" => "cursor:default;"));
			
			$content .= "<table class='elgg-table'>";
			$content .= "<tr>";
			$content .= "<th>" . elgg_echo("ntlm_login:settings:organisation:name") . "</th>";
			$content .= "<th>" . elgg_echo("ntlm_login:settings:organisation:domain") . "</th>";
			$content .= "</tr>";
			
			foreach ($matched as $organisation) {
				$content .= "<tr>";
				$content .= "<td>" . elgg_view("output/text", array("value" => $organisation["name"])) . "</td>";
				$content .= "<td>" . elgg_view("output/text", array("value" => $organisation["domain"])) . "</td>";
				$content .= "</tr>";
			}
			
			$content .= "</table>";
		} else {
			$continue = false;
			
			$content = elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:admin:verify:match:fail"), "class" => "elgg-message elgg-state-error pbn", "style" => "cursor:default;"));
		}
		
		echo elgg_view_module("inline", $title, $content);
	}
	
	// check if we can find a linked user
	if ($continue) {
		$title = elgg_echo("ntlm_login:admin:verify:user");
		$ntlm_combo = $ntlm_auth["domain"] . "/" . $ntlm_auth["username"];
		
		$user = ntlm_login_find_matched_user($ntlm_auth);
		if (!empty($user)) {
			$user_link = elgg_view("output/url", array("text" => $user->name . " (" . $user->username . ")", "href" => $user->getURL()));
			
			$content = elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:admin:verify:user:description", array($user_link, $ntlm_combo)), "class" => "elgg-message elgg-state-success pbn", "style" => "cursor:default;"));
			
		} else {
			$continue = false;
			
			$content = elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:admin:verify:user:fail", array($ntlm_combo)), "class" => "elgg-message elgg-state-error pbn", "style" => "cursor:default;"));
		}
		
		echo elgg_view_module("inline", $title, $content);
	}
	