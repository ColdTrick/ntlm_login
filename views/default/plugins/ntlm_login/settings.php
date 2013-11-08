<?php

	$tabs = array(
		array(
			"name" => "settings",
			"text" => elgg_echo("settings"),
			"href" => "admin/plugin_settings/ntlm_login",
			"selected" => true
		),
		array(
			"name" => "verify",
			"text" => elgg_echo("ntlm_login:admin:verify"),
			"href" => "admin/administer_utilities/ntlm_login",
		)
	);
	
	echo elgg_view("navigation/tabs", array("tabs" => $tabs));
	
	$plugin = elgg_extract("entity", $vars);
	
	// list organisation config
	$title = elgg_echo("ntlm_login:settings:organisations");
	
	$organisations = ntlm_login_get_organisations();
	if (!empty($organisations)) {
		$content = "<div class='mvm'>";
		
		foreach ($organisations as $index => $organisation) {
			$content .= "<div id='ntlm-login-organisation-" . $index . "'>";
			
			$content .= "<div>";
			$content .= elgg_view("output/url", array("title" => elgg_echo("delete"), "text" => elgg_view_icon("delete"), "href" => "javascript:elgg.ntlm_login.admin.delete(" . $index . ");", "rel" => elgg_echo("deleteconfirm"), "class" => "float-alt mls elgg-requires-confirmation"));
			$content .= elgg_view("output/url", array("text" => elgg_echo("edit"), "href" => "#ntlm-login-organisation-" . $index . "-form", "rel" => "toggle", "class" => "float-alt"));
			$content .= elgg_view("output/text", array("value" => $organisation["name"]));
			$content .= "</div>";
			
			$content .= "<div class='mts mbm plm hidden elgg-divide-left' id='ntlm-login-organisation-" . $index . "-form'>";
			$content .= "<div>";
			$content .= elgg_echo("ntlm_login:settings:organisation:name") . "*";
			$content .= elgg_view("input/text", array("name" => "organisations[name][]", "value" => $organisation["name"]));
			$content .= "</div>";
			$content .= "<div>";
			$content .= elgg_echo("ntlm_login:settings:organisation:domain") . "*";
			$content .= elgg_view("input/text", array("name" => "organisations[domain][]", "value" => $organisation["domain"]));
			$content .= "</div>";
			$content .= "<div>";
			$content .= elgg_echo("ntlm_login:settings:organisation:ip") . "*";
			$content .= elgg_view("input/plaintext", array("name" => "organisations[ip][]", "value" => $organisation["ip"]));
			$content .= "<div class='elgg-subtext'>" . elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:settings:ip:description"))) . "</div>";
			$content .= "</div>";
			$content .= "<div class='elgg-subtext'>" . elgg_echo("ntlm_login:settings:required") . "</div>";
			$content .= "</div>";
			
			$content .= "</div>";
		}
		
		$content .= "</div>";
	} else {
		$content = elgg_view("output/longtext", array("value" => elgg_echo("notfound"), "id" => "ntlm-login-organisations-not-found"));
	}
	
	$content .= "<div class='pbs'>" . elgg_view("output/url", array(
		"text" => elgg_echo("add"),
		"href" => "#",
		"class" => "elgg-button elgg-button-action",
		"onclick" => "elgg.ntlm_login.admin.add_organisation(this);"
	)) . "</div>";
	
	// organisation form template
	$content .= "<div class='hidden mvm' id='ntlm-login-organisation-template'>";
	$content .= "<div>";
	$content .= elgg_echo("ntlm_login:settings:organisation:name") . "*";
	$content .= elgg_view("input/text", array("name" => "organisations[name][]"));
	$content .= "</div>";
	$content .= "<div>";
	$content .= elgg_echo("ntlm_login:settings:organisation:domain") . "*";
	$content .= elgg_view("input/text", array("name" => "organisations[domain][]"));
	$content .= "</div>";
	$content .= "<div>";
	$content .= elgg_echo("ntlm_login:settings:organisation:ip") . "*";
	$content .= elgg_view("input/plaintext", array("name" => "organisations[ip][]"));
	$content .= "<div class='elgg-subtext'>" . elgg_view("output/longtext", array("value" => elgg_echo("ntlm_login:settings:ip:description"))) . "</div>";
	$content .= "</div>";
	$content .= "<div class='elgg-subtext'>" . elgg_echo("ntlm_login:settings:required") . "</div>";
	$content .= "</div>";
	
	echo elgg_view_module("inline", $title, $content);
	