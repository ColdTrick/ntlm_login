<?php

	$user_guid = (int) get_input("user_guid");
	$ntlm_hash = get_input("hash");
	
	if (!empty($user_guid) && !empty($ntlm_hash)) {
		$user = get_user($user_guid);
		
		if (!empty($user) && $user->canEdit()) {
			$ntlm_auth_hashes = $user->ntlm_auth_hash;
			
			if (!empty($ntlm_auth_hashes)) {
				$found = false;
				if (!is_array($ntlm_auth_hashes)) {
					$ntlm_auth_hashes = array($ntlm_auth_hashes);
				}
				
				foreach ($ntlm_auth_hashes as $index => $ntlm_auth_hash) {
					if ($ntlm_auth_hash === $ntlm_hash) {
						$found = true;
						unset($ntlm_auth_hashes[$index]);
						
						elgg_unset_plugin_user_setting($ntlm_hash, $user->getGUID(), "ntlm_login");
						break;
					}
				}
				
				if ($found) {
					$user->ntlm_auth_hash = $ntlm_auth_hashes;
					
					system_message(elgg_echo("ntlm_login:action:unlink:success"));
				} else {
					register_error(elgg_echo("ntlm_login:action:unlink:error:notfound"));
				}
			} else {
				register_error(elgg_echo("ntlm_login:action:unlink:error:hashes"));
			}
		} else {
			register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	forward(REFERER);