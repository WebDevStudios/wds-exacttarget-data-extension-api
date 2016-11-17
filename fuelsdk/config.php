<?php

$client_id     = wds_exacttarget_data_extension_api()->fuelsdk_config->get_client_id();
$client_secret = wds_exacttarget_data_extension_api()->fuelsdk_config->get_client_secret();

/**
 * Filter the connection settings.
 *
 * @author Aubrey Portwood
 * @since  NEXT
 */
return apply_filters( 'wds_exacttarget_data_extension_api_fuelsdk_config', array(
	'appsignature' => 'none',
	'clientid'     => $client_id,
	'clientsecret' => $client_secret,
	'defaultwsdl'  => 'https://webservice.exacttarget.com/etframework.wsdl',
	'xmlloc'       => dirname( __FILE__ ) . '/etframework.wsdl',
) );
