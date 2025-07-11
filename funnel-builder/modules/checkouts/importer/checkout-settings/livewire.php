<?php
$advanced_field = WFACP_Common::get_advanced_fields();
$settings       = [
	'show_on_next_step'          => [
		'single_step' => [
			'billing_email'       => 'true',
			'billing_first_name'  => 'true',
			'billing_last_name'   => 'true',
			'address'             => 'true',
			'shipping-address'    => 'true',
			'billing_phone'       => 'true',
			'shipping_calculator' => 'true',
		],
	],
	'enable_phone_flag'          => 'true',
	'enable_phone_validation'    => 'true',
	'autocomplete_enable'        => 'false',
	'autocomplete_google_key'    => '',
	'preferred_countries_enable' => 'false',
	'preferred_countries'        => '',
];

$steps = [
	'single_step' => [
		'name'          => __( 'Step 1', 'funnel-builder' ),
		'slug'          => 'single_step',
		'friendly_name' => __( 'Single Step Checkout', 'funnel-builder' ),
		'active'        => 'yes',
	],
	'two_step'    => [
		'name'          => __( 'Step 2', 'funnel-builder' ),
		'slug'          => 'two_step',
		'friendly_name' => __( 'Two Step Checkout', 'funnel-builder' ),
		'active'        => 'no',
	],
	'third_step'  => [
		'name'          => __( 'Step 3', 'funnel-builder' ),
		'slug'          => 'third_step',
		'friendly_name' => __( 'Three Step Checkout', 'funnel-builder' ),
		'active'        => 'no',
	],
];

$pageLayout = [
	'steps'                       => $steps,
	'fieldsets'                   => [
		'single_step' => [
			[
				'name'        => WFACP_Common::translation_string_to_check(__( 'Customer Information', 'funnel-builder' )),
				'class'       => '',
				'sub_heading' => '',
				'fields'      => [
					[
						'label'        => __( 'Email', 'woocommerce' ),
						'required'     => 'true',
						'type'         => 'email',
						'class'        => [ 0 => 'form-row-wide', ],
						'validate'     => [ 0 => 'email', ],
						'autocomplete' => 'email username',
						'priority'     => '110',
						'id'           => 'billing_email',
						'field_type'   => 'billing',
						'placeholder'  => '',
					],
					[
						'label'        => __( 'First name', 'woocommerce' ),
						'required'     => 'true',
						'class'        => [ 0 => 'form-row-first', ],
						'autocomplete' => 'given-name',
						'priority'     => '10',
						'type'         => 'text',
						'id'           => 'billing_first_name',
						'field_type'   => 'billing',
						'placeholder'  => '',
					],
					[
						'label'        => __( 'Last name', 'woocommerce' ),
						'required'     => 'true',
						'class'        => [ 0 => 'form-row-last', ],
						'autocomplete' => 'family-name',
						'priority'     => '20',
						'type'         => 'text',
						'id'           => 'billing_last_name',
						'field_type'   => 'billing',
						'placeholder'  => '',
					],


					[
						'label'        => __( 'Phone', 'woocommerce' ),
						'type'         => 'tel',
						'class'        => [ 'form-row-wide' ],
						'id'           => 'billing_phone',
						'field_type'   => 'billing',
						'validate'     => [ 'phone' ],
						'placeholder'  => '',
						'autocomplete' => 'tel',
						'priority'     => 100,
					],
				],
			],

			[
				'name'        => __( 'Shipping Address', 'woocommerce' ),
				'class'       => '',
				'sub_heading' => '',
				'fields'      => [
					WFACP_Common::get_single_address_fields( 'shipping' ),
					WFACP_Common::get_single_address_fields()
				],
			],
			[
				'name'        =>WFACP_Common::translation_string_to_check(__( 'Shipping Method', 'funnel-builder' )),
				'class'       => '',
				'sub_heading' => '',
				'html_fields' => [ 'shipping_calculator' => true ],
				'fields'      => [
					isset( $advanced_field['shipping_calculator'] ) ? $advanced_field['shipping_calculator'] : []
				],
			],
		],

	],
	'product_settings'            => [],
	'have_coupon_field'           => 'true',
	'have_billing_address'        => 'true',
	'have_shipping_address'       => 'true',
	'have_billing_address_index'  => '7',
	'have_shipping_address_index' => '6',
	'enabled_product_switching'   => 'yes',
	'have_shipping_method'        => 'true',
	'current_step'                => 'single_step',
];


return [
	'page_layout'   => $pageLayout,
	'page_settings' => $settings,
];
