<?php

	$flag = elgg_extract("ntlm_login_flag", $_SESSION, false);
	
	// did we get a succesfull NTLM auth
	if (!empty($flag)) {
		$ntlm_user = ntlm_login_get_local_user();
		
		$ntlm_combo = elgg_extract("domain", $ntlm_user) . "/" . elgg_extract("username", $ntlm_user);
		$site = elgg_get_site_entity();
		
		echo "<div>";
		echo elgg_view("input/checkbox", array("name" => "ntlm_login_link", "value" => 1, "id" => "ntlm-login-link"));
		echo "<label for='ntlm-login-link'>" . elgg_echo("ntlm_login:login_extend:link", array($ntlm_combo, $site->name)) . "</label>";
		echo "</div>";
	}