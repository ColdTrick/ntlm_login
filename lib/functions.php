<?php

	/**
	 * This function is used by the PHP-NTLM lib to verify the NTML message
	 * However to correctly veryfy this message the user password has to be checked.
	 * For now we're not interested in this
	 *
	 * @param string $challenge
	 * @param string $user
	 * @param string $domain
	 * @param string $workstation
	 * @param string $clientblobhash
	 * @param string $clientblob
	 * @param string $get_ntlm_user_hash_callback
	 * @return boolean
	 */
	function ntlm_login_verify_hash_callback($challenge, $user, $domain, $workstation, $clientblobhash, $clientblob, $get_ntlm_user_hash_callback) {
		// for now just return true
		return true;
	}
	
	function ntlm_login_get_client_ip() {
		$result = false;
		
		if (getenv("HTTP_CLIENT_IP")) {
			$result = getenv("HTTP_CLIENT_IP");
		} elseif (getenv("HTTP_X_FORWARDED_FOR")) {
			$result = getenv("HTTP_X_FORWARDED_FOR");
		} elseif (getenv("HTTP_X_FORWARDED")) {
			$result = getenv("HTTP_X_FORWARDED");
		} elseif (getenv("HTTP_FORWARDED_FOR")) {
			$result = getenv("HTTP_FORWARDED_FOR");
		} elseif (getenv("HTTP_FORWARDED")) {
			$result = getenv("HTTP_FORWARDED");
		} else {
			$result = $_SERVER["REMOTE_ADDR"];
		}
		
		return $result;
	}
	
	function ntlm_login_get_local_user() {
		$result = false;
		
		elgg_load_library("loune.php-ntlm");
		
		$auth = ntlm_prompt(elgg_get_site_url(), "dummy_domain", "dummy_computer", "dummy.domain", "dummy.dns_server", null, "ntlm_login_verify_hash_callback");
		
		if (!empty($auth)) {
			if (elgg_extract("authenticated", $auth)) {
				$result = $auth;
			}
		}
		
		return $result;
	}
	
	/**
	 * Get the organisation information based on a client IP-address
	 *
	 * @param string $client_ip the IP-address of the user to check
	 * @return bool|array false or organisation information
	 */
	function ntlm_login_get_organisations_by_ip($client_ip) {
		$result = false;
		
		if (!empty($client_ip)) {
			$organisations = ntlm_login_get_organisations();
			
			if (!empty($organisations)) {
				elgg_load_library("pgregg.ipcheck");
				$temp = array();
				
				foreach ($organisations as $organisation) {
					$ip_config = $organisation["ip"];
					
					$ip_config = explode(PHP_EOL, $ip_config);
					
					foreach ($ip_config as $ip_address) {
						
						if (ip_in_range($client_ip, $ip_address)) {
							$temp[] = $organisation;
							break;
						}
					}
				}
				
				if (!empty($temp)) {
					$result = $temp;
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Get all the configured organisations
	 *
	 * @return bool|array
	 */
	function ntlm_login_get_organisations() {
		$result = false;
		
		$setting = elgg_get_plugin_setting("organisations", "ntlm_login");
		if (!empty($setting)) {
			$result = json_decode($setting, true);
		}
		
		return $result;
	}
	
	/**
	 * Match an ntlm user domain to the allowed organisations for the current IP address
	 *
	 * @param array $ntlm_user @see ntlm_login_get_local_user()
	 * @param array $organisations @see ntlm_login_get_organisations_by_ip()
	 * @return bool|array
	 */
	function ntlm_login_match_user_to_organisation($ntlm_user, $organisations) {
		$result = false;
		
		if (!empty($ntlm_user)  && is_array($ntlm_user) && !empty($organisations) && is_array($organisations)) {
			$user_domain = elgg_extract("domain", $ntlm_user);
			
			if (!empty($user_domain)) {
				$temp = array();
				
				foreach ($organisations as $organisation) {
					$organisation_domain = elgg_extract("domain", $organisation);
					
					if (!empty($organisation_domain) && ($user_domain == $organisation_domain)) {
						$temp[] = $organisation;
					}
				}
				
				if (!empty($temp)) {
					$result = $temp;
				}
			}
		}
		
		return $result;
	}
	
	/**
	 * Find a user based on the NTLM authentication information
	 *
	 * @param array $ntlm_user @see ntlm_login_get_local_user()
	 * @return bool|array
	 */
	function ntlm_login_find_matched_user($ntlm_user) {
		$result = false;
		
		if (!empty($ntlm_user) && is_array($ntlm_user)) {
			$ntlm_username = elgg_extract("username", $ntlm_user);
			$ntlm_domain = elgg_extract("domain", $ntlm_user);
			
			if (!empty($ntlm_username) && !empty($ntlm_domain)) {
				$site_secret = get_site_secret();
				$ntlm_combo = $ntlm_domain . "/" . $ntlm_username;
				
				$options = array(
					"type" => "user",
					"limit" => false,
					"metadata_name_value_pairs" => array(
						"name" => "ntlm_auth_hash",
						"value" => hash_hmac("sha256", $ntlm_combo, $site_secret)
					)
				);
				
				// ignore access (for private metadata)
				$ia = elgg_set_ignore_access(true);
				
				$users = elgg_get_entities_from_metadata($options);
				if (!empty($users) && (count($users) == 1)) {
					$result = $users[0];
				}
				
				// restore access
				elgg_set_ignore_access($ia);
			}
		}
		
		return $result;
	}
	
	function ntlm_login_try_login() {
		
		if (!elgg_is_logged_in()) {
			// get the IP address of the user
			$client_ip = ntlm_login_get_client_ip();
			
			// get organisations matching the IP address
			$configured_organisations = ntlm_login_get_organisations_by_ip($client_ip);
			
			if (!empty($configured_organisations)) {
				// get a NTLM user
				$ntlm_user = ntlm_login_get_local_user();
				$_SESSION["ntlm_login_flag"] = !empty($ntlm_user);
				
				if (!empty($ntlm_user)) {
					// match the user to an organisation
					$matched_organisations = ntlm_login_match_user_to_organisation($ntlm_user, $configured_organisations);
					
					if (!empty($matched_organisations)) {
						// can we find a linked user
						$user = ntlm_login_find_matched_user($ntlm_user);
						
						if (!empty($user)) {
							// found a user, so login
							try {
								login($user, true);
								// re-register at least the core language file for users with language other than site default
								register_translations(dirname(dirname(__FILE__)) . "/languages/");
								
								system_message(elgg_echo("loginok"));
								
								$forward_url = "";
								if (!empty($_SESSION["last_forward_from"])) {
									$forward_url = $_SESSION["last_forward_from"];
									unset($_SESSION["last_forward_from"]);
								}
								
								forward($forward_url);
							} catch (LoginException $e) {
								register_error($e->getMessage());
								
								forward(current_page_url());
							}
						} else {
							// no user found, offer the option to link
							$_SESSION["last_forward_from"] = current_page_url();
							
							forward("login");
						}
					}
				}
			} else {
				// prevent further attempts
				$_SESSION["ntlm_login_flag"] = false;
			}
		}
	}
	