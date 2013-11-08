<?php
$language = array (
  'admin:administer_utilities:ntlm_login' => 'NTLM Login controleer configuratie',
  'ntlm_login:admin:verify' => 'Controleer configuratie',
  'ntlm_login:admin:verify:ntlm' => 'NTLM aanroep',
  'ntlm_login:admin:verify:ntlm:description' => 'Je browser is geconfigureerd voor NTLM en we hebben de volgende informatie ontvangen.

Als er een pop-up kwam die vroeg om je aanmeld gegevens, controleer dan dat deze website is toegevoegd aan de Intranet Zone van Internet Explorere en de vertrouwde URIs van Firefox. Vraag uw systeembeheerder voor meer informatie.',
  'ntlm_login:admin:verify:ntlm:name' => 'Naam',
  'ntlm_login:admin:verify:ntlm:value' => 'Waarde',
  'ntlm_login:admin:verify:ntlm:username' => 'Gebruikersnaam',
  'ntlm_login:admin:verify:ntlm:workstation' => 'Computernaam',
  'ntlm_login:admin:verify:ntlm:ip' => 'Je IP adres',
  'ntlm_login:admin:verify:ntlm:fail' => 'De NTLM aanroep is mislukt, controleer of je browser NTLM ondersteund en dat de URL van deze website is toegevoegd aan de Intranet Zone van Internet Explorere en de vertrouwde URIs van Firefox. Vraag uw systeembeheerder voor meer informatie.',
  'ntlm_login:admin:verify:organisation' => 'Geconfigureerde organisaties',
  'ntlm_login:admin:verify:organisation:description' => 'De volgende organisaties zijn geconfigureerd voor je IP adres (%s).',
  'ntlm_login:admin:verify:organisation:fail' => 'Er is geen organisatie geconfigureerd voor je IP adres %s. Als je een organisatie wilt toevoegen voor dit IP adres, ga dan naar de instellingen pagina.',
  'ntlm_login:admin:verify:match' => 'Gevonden organisaties',
  'ntlm_login:admin:verify:match:description' => 'De volgende organisaties kunnen overeenkomen met jou als gebruiker.',
  'ntlm_login:admin:verify:match:fail' => 'Er konden geen organisaties worden gevonden die overeenkomen met jou als gebruiker op het huidige IP adres.',
  'ntlm_login:admin:verify:user' => 'Gevonden gebruikers',
  'ntlm_login:admin:verify:user:fail' => 'Er kon geen gebruiker worden gevonden voor %s.',
  'ntlm_login:admin:verify:user:description' => 'De gebruiker %s komt overeen met %s. Deze gebruiker kan zich automatisch aanmelden op de website op deze computer.',
  'ntlm_login:login_extend:link' => 'Koppel dit computer account (%s) aan mijn %s account.',
  'ntlm_login:settings:organisations' => 'Organisatie configuratie',
  'ntlm_login:settings:organisation:name' => 'Naam',
  'ntlm_login:settings:organisation:domain' => 'Domeinnaam',
  'ntlm_login:settings:organisation:ip' => 'IP adressen',
  'ntlm_login:settings:required' => 'Velden gemarkeerd met een * zijn verplicht',
  'ntlm_login:settings:ip:description' => 'IP adressen moeten worden geconfigureerd één per regel in één van de onderstaande formaten:
1. Wildcard formaat: 1.2.3.*
2. CIDR formaat: 1.2..3/24 of 1.2.3.4/255.255.255.0
3. Start-Eind IP formaat: 1.2.3.0-1.2.3.255',
  'ntlm_login:usersettings:not_connected:description' => 'Je account is nog niet gekoppeld aan een computer/domein account.',
  'ntlm_login:usersettings:connected:description' => 'Je account is gekoppeld aan één of meerdere computer/domein accounts. Hieronder staat een lijst van de gekoppelde computer/domein accounts, als je wilt kun je ze vanaf hier verwijderen.',
  'ntlm_login:action:settings:organisation' => 'Er is een fout in één van de organisatie configuraties. Controleer alle velden (naam: %s, domein: %s, IP adres: %s)',
  'ntlm_login:action:unlink:error:hashes' => 'De opgegeven gebruiker heeft geen active NTLM koppelingen',
  'ntlm_login:action:unlink:error:notfound' => 'De opgegeven NTLM koppeling kon niet worden gevonden bij de opgegeven gebruiker',
  'ntlm_login:action:unlink:success' => 'De NTLM koppeling is verwijderd',
);
add_translation("nl", $language);
