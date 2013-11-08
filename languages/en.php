<?php

	$english = array(
		// general
		'admin:administer_utilities:ntlm_login' => "NTLM Login verify configuration",
		'ntlm_login:admin:verify' => "Verify configuration",
		
		// pages
		// verify configuration
		'ntlm_login:admin:verify:ntlm' => "NTLM challenge",
		'ntlm_login:admin:verify:ntlm:description' => "Your browser is configured for NTLM and we got the following information about you.
		
If there was a pop-up asking you for your credentials please make sure that this website is added to the Intranet Zone for Internet Explorer and the trusted URIs for Firefox. Check with your system administrator for more information.",
		'ntlm_login:admin:verify:ntlm:name' => "Name",
		'ntlm_login:admin:verify:ntlm:value' => "Value",
		'ntlm_login:admin:verify:ntlm:username' => "Username",
		'ntlm_login:admin:verify:ntlm:workstation' => "Workstation name",
		'ntlm_login:admin:verify:ntlm:ip' => "Your IP address",
		'ntlm_login:admin:verify:ntlm:fail' => "The NTLM challenge failed, please make sure that your browser supports NTLM and that the URL of this website is added to the Intranet Zone for Internet Exploere and the trusted URIs for Firefox. Check with your system administrator for more information.",
		
		'ntlm_login:admin:verify:organisation' => "Configured organisations",
		'ntlm_login:admin:verify:organisation:description' => "The following organisations are configured for your IP address (%s).",
		'ntlm_login:admin:verify:organisation:fail' => "No organisation is configured for your IP address %s. If you wish to add an organisation for this IP address, please go to the settings page.",
		
		'ntlm_login:admin:verify:match' => "Matched organisations",
		'ntlm_login:admin:verify:match:description' => "The following organisations can be matched to you as an user.",
		'ntlm_login:admin:verify:match:fail' => "No organisations could be matched to you as an user on your current IP address.",
		
		'ntlm_login:admin:verify:user' => "Matched users",
		'ntlm_login:admin:verify:user:fail' => "No user could be matched to %s.",
		'ntlm_login:admin:verify:user:description' => "The user %s could be matched to %s. This user can automaticly login to the site on this workstation.",
		
		// login extend
		'ntlm_login:login_extend:link' => "Link this computer account (%s) to my %s account.",
		'' => "",
		
		// plugin settings
		'ntlm_login:settings:organisations' => "Organisation configuration",
		'ntlm_login:settings:organisation:name' => "Name",
		'ntlm_login:settings:organisation:domain' => "Domainname",
		'ntlm_login:settings:organisation:ip' => "IP addresses",
		
		'ntlm_login:settings:required' => "Fields marked with a * are required",
		'ntlm_login:settings:ip:description' => "IP addresses have to be configured one per line in one of the following formats:
1. Wildcard format:     1.2.3.*
2. CIDR format:         1.2.3/24  OR  1.2.3.4/255.255.255.0
3. Start-End IP format: 1.2.3.0-1.2.3.255",
		
		// user settings
		'ntlm_login:usersettings:not_connected:description' => "Your account isn't yet linked to any computer/domain accounts.",
		'ntlm_login:usersettings:connected:description' => "Your account is linked to one or more computer/domain accounts. Below you'll find a list of the computer/domain accounts, you can remove them is you want.",
		
		// actions
		// plugin settings
		'ntlm_login:action:settings:organisation' => "There was an error in the configuration of one of the organisations, please check all the fields (name: %s, domain: %s, IP addresses: %s)",
		
		// unlink
		'ntlm_login:action:unlink:error:hashes' => "The provided user doesn't have any active NTLM links",
		'ntlm_login:action:unlink:error:notfound' => "The provided NTLM link doesn't exists with the provided user",
		'ntlm_login:action:unlink:success' => "The NTLM link was removed",
		'' => "",
	);
	
	add_translation("en", $english);