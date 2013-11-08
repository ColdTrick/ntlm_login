<?php ?>
//<script>
elgg.provide("elgg.ntlm_login.admin");

elgg.ntlm_login.admin.add_organisation = function(button) {
	$(button).parent().before($('#ntlm-login-organisation-template').clone().removeClass("hidden").attr("id", ""));
	$("#ntlm-login-organisations-not-found").hide();
}

elgg.ntlm_login.admin.delete = function(index) {
	$("#ntlm-login-organisation-" + index).remove();
}