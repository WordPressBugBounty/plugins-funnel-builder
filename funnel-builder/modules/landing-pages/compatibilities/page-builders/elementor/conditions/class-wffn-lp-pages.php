<?php

namespace ElementorPro\Modules\ThemeBuilder\Conditions;

use ElementorPro\Modules\QueryControl\Module as QueryModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( ! class_exists( '\ElementorPro\Modules\ThemeBuilder\Conditions\WFFN_LP_Pages' ) ) {
	#[AllowDynamicProperties]
	class WFFN_LP_Pages extends Post {

		public function get_label() {
			return 'FunnelKit Sale Page';
		}


		public function register_sub_conditions() {
		}


	}
}
