<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * plugin Name: Borlabs Cookie - Cookie Opt-in v.2.2.67 by Borlabs GmbH
 *
 */


#[AllowDynamicProperties]
class WFACP_Borlabs_Cookie_Opt_In {
	public function __construct() {

		/**
		 * Template Redirect conflict issue resolved
		 */
		add_action( 'wfacp_checkout_page_found', [ $this, 'remove_action' ] );
	}


	public function remove_action() {
		remove_action( 'template_redirect', [ BorlabsCookie\Cookie\Frontend\Buffer::getInstance(), 'handleBuffering' ], 19021987 );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Borlabs_Cookie_Opt_In(), 'borlabs_cookie' );


