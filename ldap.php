<?php

							$adServer = "14.141.214.226";
								$ldap = ldap_connect($adServer);
								$username = 'ffms@clicktable.com';
								$password = 'Ct@4321';
								$ldaprdn =  $username;
								ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
								ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
								$bind = @ldap_bind($ldap, $ldaprdn, $password);
								if ($bind) 
								{
								
								echo "hi";
}
?>