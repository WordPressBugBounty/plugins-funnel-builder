<?php

namespace ElementorPro\Modules\ThemeBuilder\Conditions;

use ElementorPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( '\ElementorPro\Modules\ThemeBuilder\Conditions\WFFN_OTY_Pages' ) ) {
	class WFFN_OTY_Pages extends Post {

		public function get_label() {
			return 'FunnelKit Optin Confirmation';
		}


		public function register_sub_conditions() {
		}


	}
}