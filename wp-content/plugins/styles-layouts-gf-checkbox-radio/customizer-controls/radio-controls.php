<?php 

// radio enable disable option.
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][enabled]',
	array(
		'default' => false,
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][enabled]',
	array(
		'type'     => 'checkbox',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_radio', // Required, core or custom.
		'label'    => __( 'Enable Custom Radio' ),

	)
);

// Type of radio
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][type]',
	array(
		'default' => 'default',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][type]',
	array(
		'type'     => 'select',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_radio', // Required, core or custom.
		'label'    => __( 'Radio Type' ),
		'choices'   => $radio_type,
	)
);

$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][shape]',
	array(
		'default' => 'round',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][shape]',
	array(
		'type'     => 'radio',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_radio', // Required, core or custom.
		'label'    => __( 'Shape' ),
		'choices'   => $basic_checkbox_type,
	)
);

// basic styles
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][style]',
	array(
		'default' => 'default',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][style]',
	array(
		'type'     => 'radio',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_radio', // Required, core or custom.
		'label'    => __( 'Basic Style' ),
		'choices'   => $basic_checkbox_style,
	)
);

// select icon
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][fontawesome-icon]',
	array(
		'default' => 'fas fa-check',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][fontawesome-icon]',
	array(
		'type'     => 'select',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_radio', // Required, core or custom.
		'label'    => __( 'Select Icon' ),
		'choices'   => $fontawesome_icons,
	)
);

// switch radio types

$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][switch_type]',
	array(
		'default' => 'outline',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][switch_type]',
	array(
		'type'     => 'radio',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_radio', // Required, core or custom.
		'label'    => __( 'Switch Type' ),
		'choices'   => $switch_type,
	)
);

// Unchecked color

$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][uncheck-color]',
	array(
		'default' => '#bdc3c7',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, // WP_Customize_Manager
		'gf_stla_checkbox_radio_' . $current_form_id . '[radio][uncheck-color]', // Setting id
		array( // Args, including any custom ones.
			'label' => __( 'Unchecked Color' ),
			'section' => 'gf_stla_custom_radio',
		)
	)
);

// Checked color

$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][checked-color]',
	array(
		'default' => '#bdc3c7',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, // WP_Customize_Manager
		'gf_stla_checkbox_radio_' . $current_form_id . '[radio][checked-color]', // Setting id
		array( // Args, including any custom ones.
			'label' => __( 'Checked color' ),
			'section' => 'gf_stla_custom_radio',
		)
	)
);



// radio scale

// $wp_customize->add_setting(
// 	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][size]',
// 	array(
// 		'default' => '',
// 		'type'    => 'option',
// 	)
// );

// $wp_customize->add_control(
// 	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][size]',
// 	array(
// 		'type'     => 'text',
// 		'priority' => 10, // Within the section.
// 		'section'  => 'gf_stla_custom_radio', // Required, core or custom.
// 		'label'    => __( 'Size' )
// 	)
// );

// Radio Animation

$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][animation]',
	array(
		'default' => 'smooth',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[radio][animation]',
	array(
		'type'     => 'select',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_radio', // Required, core or custom.
		'label'    => __( 'Animations' ),
		'choices'   => $animations,
	)
);
