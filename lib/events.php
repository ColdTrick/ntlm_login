<?php

	function ntlm_login_login_event_handler($event, $type, $entity) {
		
		if (!empty($entity) && elgg_instanceof($entity, "user")) {
			// can we link an account
			$flag = elgg_extract("ntlm_login_flag", $_SESSION);
			
			if (!empty($flag)) {
				// did the user want to link the accounts
				$link = (int) get_input("ntlm_login_link");
				
				if (!empty($link)) {
					// get the NTLM user
					$ntlm_user = ntlm_login_get_local_user();
					
					if (!empty($ntlm_user)) {
						$ntlm_combo = elgg_extract("domain", $ntlm_user) . "/" . elgg_extract("username", $ntlm_user);
						$site_secret = get_site_secret();
						$ntlm_hash = hash_hmac("sha256", $ntlm_combo, $site_secret);
						
						// find existing users with this combo (shouldn't be any)
						$metadata_options = array(
							"type" => "user",
							"limit" => false,
							"metadata_name" => "ntlm_auth_hash",
							"metadata_value" => $ntlm_hash
						);
						
						// prevent access limitations
						$ia = elgg_set_ignore_access(true);
						
						$metadatas = elgg_get_metadata($metadata_options);
						
						// restore access
						elgg_set_ignore_access($ia);
						
						if (!empty($metadatas)) {
							elgg_push_context("ntlm_login_metadata_cleanup");
							
							foreach ($metadatas as $metadata) {
								// unset the plugin settings which logs data
								elgg_unset_plugin_user_setting($ntlm_hash, $metadata->getOwnerGUID(), "ntlm_login");
								
								// remove the metadata
								$metadata->delete();
							}
							
							elgg_pop_context();
						}
						
						// save the information to the new user
						$res = create_metadata($entity->getGUID(), "ntlm_auth_hash", $ntlm_hash, null, $entity->getGUID(), ACCESS_PRIVATE, true);
						
						elgg_set_plugin_user_setting($ntlm_hash, $ntlm_combo, $entity->getGUID(), "ntlm_login");
					}
				}
			}
		}
	}
	