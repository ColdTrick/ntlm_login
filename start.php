<?php

	require_once(dirname(__FILE__) . "/lib/events.php");
	require_once(dirname(__FILE__) . "/lib/functions.php");
	
	elgg_register_event_handler("plugins_boot", "system", "ntlm_login_plugins_boot");
	elgg_register_event_handler("init", "system", "ntlm_login_init");
	
	function ntlm_login_plugins_boot() {
		// register a vendor lib
		elgg_register_library("loune.php-ntlm", dirname(__FILE__) . "/vendors/php_ntlm/ntlm.php");
		elgg_register_library("pgregg.ipcheck", dirname(__FILE__) . "/vendors/pgregg/ip_check.php");
		
		// do we need to try a NTLM login
		if (!isset($_SESSION["ntlm_login_flag"]) && !elgg_is_logged_in()) {
			// try a login
			ntlm_login_try_login();
		}
	}
	
	function ntlm_login_init() {
		// extend css / js
		elgg_extend_view("js/admin", "js/ntlm_login/admin");
		
		// extend views
		elgg_extend_view("login/extend", "ntlm_login/login");
		
		// register event handlers
		elgg_register_event_handler("login", "user", "ntlm_login_login_event_handler");
		
		// register actions
		elgg_register_action("ntlm_login/settings/save", dirname(__FILE__) . "/actions/settings.php", "admin");
		elgg_register_action("ntlm_login/unlink", dirname(__FILE__) . "/actions/unlink.php");
	}