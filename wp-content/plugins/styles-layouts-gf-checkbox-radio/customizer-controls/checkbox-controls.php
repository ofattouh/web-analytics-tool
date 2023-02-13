<?php 

// Checkbox enable disable option.
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][enabled]',
	array(
		'default' => false,
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][enabled]',
	array(
		'type'     => 'checkbox',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_checkbox', // Required, core or custom.
		'label'    => __( 'Enable Custom Checkbox' ),

	)
);
// Type of Checkbox
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][type]',
	array(
		'default' => 'default',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][type]',
	array(
		'type'     => 'select',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_checkbox', // Required, core or custom.
		'label'    => __( 'Checkbox Type' ),
		'choices'   => $checkbox_type,
	)
);

// Type of basic checkbox
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][shape]',
	array(
		'default' => 'default',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][shape]',
	array(
		'type'     => 'radio',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_checkbox', // Required, core or custom.
		'label'    => __( 'Shape' ),
		'choices'   => $basic_checkbox_type,
	)
);


// basic styles
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][style]',
	array(
		'default' => 'default',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][style]',
	array(
		'type'     => 'radio',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_checkbox', // Required, core or custom.
		'label'    => __( 'Basic Style' ),
		'choices'   => $basic_checkbox_style,
	)
);

// switch checkbox types

$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][switch]',
	array(
		'default' => 'outline',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][switch]',
	array(
		'type'     => 'radio',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_checkbox', // Required, core or custom.
		'label'    => __( 'Switch Type' ),
		'choices'   => $switch_type,
	)
);
// upload Image
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][image]',
	array(
		'default' => '',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	new WP_Customize_Image_Control(
			 $wp_customize,
			 'gf_stla_checkbox_radio_'.$current_form_id.'[checkbox][image]',
			 array(
				 'label'      => 'Checkbox Image' ,
				 'section'    => 'gf_stla_custom_checkbox',
				 'settings'   => 'gf_stla_checkbox_radio_'.$current_form_id.'[checkbox][image]',
	  )
	)
  );

// select icon
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][fontawesome-icon]',
	array(
		'default' => 'fas fa-check',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][fontawesome-icon]',
	array(
		'type'     => 'select',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_checkbox', // Required, core or custom.
		'label'    => __( 'Fontawesome Icon' ),
		'choices'   => $fontawesome_icons,
	)
);



// unchecked color
$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][uncheck-color]',
	array(
		'default' => '#bdc3c7',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, // WP_Customize_Manager
		'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][uncheck-color]', // Setting id
		array( // Args, including any custom ones.
			'label' => __( 'Unchecked Color' ),
			'section' => 'gf_stla_custom_checkbox',
		)
	)
);

// checked color Theme

$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][checked-color]',
	array(
		'default' => '#bdc3c7',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, // WP_Customize_Manager
		'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][checked-color]', // Setting id
		array( // Args, including any custom ones.
			'label' => __( 'Checked Color' ),
			'section' => 'gf_stla_custom_checkbox',
		)
	)
);

// checkbox scale

// $wp_customize->add_setting(
// 	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][size]',
// 	array(
// 		'default' => '',
// 		'type'    => 'option',
// 	)
// );

// $wp_customize->add_control(
// 	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][size]',
// 	array(
// 		'type'     => 'text',
// 		'priority' => 10, // Within the section.
// 		'section'  => 'gf_stla_custom_checkbox', // Required, core or custom.
// 		'label'    => __( 'Size' )
// 	)
// );

// checkbox Animation

$wp_customize->add_setting(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][animation]',
	array(
		'default' => 'smooth',
		'type'    => 'option',
	)
);

$wp_customize->add_control(
	'gf_stla_checkbox_radio_' . $current_form_id . '[checkbox][animation]',
	array(
		'type'     => 'select',
		'priority' => 10, // Within the section.
		'section'  => 'gf_stla_custom_checkbox', // Required, core or custom.
		'label'    => __( 'Animations' ),
		'choices'   => $animations,
	)
);



