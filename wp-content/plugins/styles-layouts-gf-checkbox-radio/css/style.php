<?php 
$customizer_radio_checkbox_options = get_option("gf_stla_checkbox_radio_". $form_id);
$customizer_checkbox_options = isset( $customizer_radio_checkbox_options['checkbox'] ) ? $customizer_radio_checkbox_options['checkbox']: false;
$customizer_radio_options = isset( $customizer_radio_checkbox_options['radio'] ) ? $customizer_radio_checkbox_options['radio'] : false ;

ob_start();
?>
<style type= "text/css">
#gform_wrapper_<?php echo $form_id ?> .gform_body .gform_fields .gfield .gfield_checkbox .pretty.p-default .state label:before,
#gform_wrapper_<?php echo $form_id ?> .gform_body .gform_fields .gfield .gfield_checkbox .pretty.p-icon .state label:before{
    border-color: <?php echo ! empty( $customizer_checkbox_options["uncheck-color"] ) ? $customizer_checkbox_options["uncheck-color"] : '#bdc3c7'; ?>;
}
#gform_wrapper_<?php echo $form_id ?> .gform_body .gform_fields .gfield .gfield_checkbox .pretty.p-default input:checked ~ .state label:before,
#gform_wrapper_<?php echo $form_id ?> .gform_body .gform_fields .gfield .gfield_checkbox .pretty.p-icon input:checked ~ .state label:before{
    border-color: <?php echo ! empty( $customizer_checkbox_options["checked-color"] ) ? $customizer_checkbox_options["checked-color"] : '#bdc3c7'; ?>;
}
#gform_wrapper_<?php echo $form_id ?> .gform_body .gform_fields .gfield .gfield_checkbox .pretty.p-default input:checked ~ .state label:after
#gform_wrapper_<?php echo $form_id ?> .gform_body .gform_fields .gfield .gfield_checkbox .pretty.p-icon input:checked ~ .state label:after{
    border-color:  <?php echo ! empty( $customizer_checkbox_options["checked-color"]  ) ? $customizer_checkbox_options["checked-color"]: "" ; ?> !important;
}
#gform_wrapper_<?php echo $form_id ?> .gform_body .gform_fields .gfield .gfield_checkbox .pretty.p-default input:checked ~ .state label:after{
    background-color:  <?php echo ! empty( $customizer_checkbox_options["checked-color"]) ? $customizer_checkbox_options["checked-color"] : "" ; ?> !important;

}
/* checkbox icon */
#gform_wrapper_<?php echo $form_id ?> .gform_body .gform_fields .gfield .gfield_checkbox .pretty.p-icon input:checked ~ .state i{
    color:  <?php echo ! empty( $customizer_checkbox_options["checked-color"]  ) ? $customizer_checkbox_options["checked-color"]: "" ; ?> !important;
}
/* checkbox switches */

#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_checkbox .pretty.p-switch .state:before{
    border-color: <?php echo ! empty( $customizer_checkbox_options["uncheck-color"] ) ? $customizer_checkbox_options["uncheck-color"] : '#bdc3c7'; ?>;
}
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_checkbox .pretty.p-switch .state label:after{
    background-color: <?php echo ! empty( $customizer_checkbox_options["uncheck-color"] ) ? $customizer_checkbox_options["uncheck-color"] : '#bdc3c7'; ?> !important;
}

#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_checkbox .pretty.p-switch input:checked ~ .state:before{
    border-color: <?php echo ! empty( $customizer_checkbox_options["checked-color"] ) ? $customizer_checkbox_options["checked-color"] : '#bdc3c7'; ?>;
}
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_checkbox .pretty.p-switch input:checked ~ .state label:after{
    background-color: <?php echo ! empty( $customizer_checkbox_options["checked-color"] ) ? $customizer_checkbox_options["checked-color"] : '#bdc3c7'; ?> !important;
}

/* fill switches */
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_checkbox .pretty.p-switch.p-fill input:checked ~ .state label:after{
    background-color: #fff !important;
}

#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_checkbox .pretty.p-switch.p-fill input:checked ~ .state:before,
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_checkbox .pretty.p-switch.p-slim input:checked ~ .state:before{
    background-color: <?php echo ! empty( $customizer_checkbox_options["checked-color"] ) ? $customizer_checkbox_options["checked-color"] : '#bdc3c7'; ?> !important;
}
/* slim switch */
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_checkbox .pretty.p-switch.p-slim .state:before{
    background-color: <?php echo ! empty( $customizer_checkbox_options["uncheck-color"] ) ? $customizer_checkbox_options["uncheck-color"] : '#bdc3c7'; ?> !important;
}

/* ------------------------------- RADIO STYLES ------------------------- */
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-default .state label:before,
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-icon .state label:before{
    border-color: <?php echo ! empty( $customizer_radio_options["uncheck-color"] ) ? $customizer_radio_options["uncheck-color"] : '#bdc3c7'; ?>;
}
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield_radio .pretty.p-default input:checked ~ .state label:before{
    border-color: <?php echo ! empty( $customizer_radio_options["checked-color"] ) ? $customizer_radio_options["checked-color"] : '#bdc3c7'; ?>;
}

#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield_radio .pretty input:checked ~ .state label:after{
    border-color: <?php echo ! empty( $customizer_radio_options["checked-color"] ) ? $customizer_radio_options["checked-color"] : '#bdc3c7'; ?>;
   
}
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield_radio .pretty.p-default input:checked ~ .state label:after{
    background-color: <?php echo ! empty( $customizer_radio_options["checked-color"] ) ? $customizer_radio_options["checked-color"] : '#bdc3c7'; ?> !important;
}
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield_radio .pretty.p-icon input:checked ~ .state i{
    color: <?php echo ! empty( $customizer_radio_options["checked-color"] ) ? $customizer_radio_options["checked-color"] : '#bdc3c7'; ?> !important;
}
/*  radio switch */
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-switch input ~ .state:before{
    border-color: <?php echo ! empty( $customizer_radio_options["uncheck-color"] ) ? $customizer_radio_options["uncheck-color"] : '#bdc3c7'; ?>;

}
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-switch input:checked ~ .state:before{
    border-color: <?php echo ! empty( $customizer_radio_options["checked-color"] ) ? $customizer_radio_options["checked-color"] : '#bdc3c7'; ?>;

}

#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-switch .state label:after{

    background-color: <?php echo ! empty( $customizer_radio_options["uncheck-color"] ) ? $customizer_radio_options["uncheck-color"] : '#bdc3c7'; ?> !important;
}


#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-switch input:checked ~ .state label:after{

    background-color: <?php echo ! empty( $customizer_radio_options["checked-color"] ) ? $customizer_radio_options["checked-color"] : '#bdc3c7'; ?> !important;
}


/* slim switch */
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-switch.p-slim .state:before{
    background-color: <?php echo ! empty( $customizer_radio_options["uncheck-color"] ) ? $customizer_radio_options["uncheck-color"] : '#bdc3c7'; ?> !important;
}

#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-switch.p-slim input:checked ~ .state:before, 
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-switch.p-fill input:checked ~ .state:before{
    background-color: <?php echo ! empty( $customizer_radio_options["checked-color"] ) ? $customizer_radio_options["checked-color"] : '#bdc3c7'; ?> !important;
}
/* fill switch */
#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-switch.p-fill input:checked ~ .state:before{
    background-color: <?php echo ! empty( $customizer_radio_options["checked-color"] ) ? $customizer_radio_options["checked-color"] : '#bdc3c7'; ?> !important;
}

#gform_wrapper_<?php echo $form_id ?>.gform_wrapper .gform_fields .gfield .gfield_radio .pretty.p-switch.p-fill input:checked ~ .state label:after{

background-color: #fff !important;
}
</style>

<?php
$styles = ob_get_contents();
ob_end_clean();
echo $styles;