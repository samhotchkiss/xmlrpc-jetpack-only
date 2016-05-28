<?php
/*
Plugin Name: XML-RPC for Jetpack Only
Plugin URI: http://wordpress.org/plugins/xmlrpc-jetpack-only/
Description: Love your Jetpack but under siege by XML-RPC attacks?  This plugin will block all XML-RPC activity that doesn't originate from the Jetpack servers.
Author: Sam Hotchkiss
License: GPL2+
Version: 1.0
Author URI: http://samhotchkiss.wordpress.com/
*/

function xmlrpc_jetpack_filter() {
	
	if( function_exists( 'jetpack_protect_get_ip' ) ) {
		$ip = jetpack_protect_get_ip();
	} else {
		$ip = $_SERVER[ 'REMOTE_ADDR' ];
	}
	
	
	if( ! xmlrpcjf_cidr_match( $ip, '192.0.64.0/18' ) ) {
		die( 'XML-RPC is not supported from your IP.' );
	}
	
}

function xmlrpcjf_cidr_match($ip, $cidr)
{
	list($subnet, $mask) = explode('/', $cidr);

	if ((ip2long($ip) & ~((1 << (32 - $mask)) - 1) ) == ip2long($subnet))
	{ 
		return true;
	}

	return false;
}

if( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
	add_action( 'plugins_loaded', 'xmlrpc_jetpack_filter', 200 ); // Load after Jetpack so we can use jetpack_protect_get_ip
}

?>
