( function( $ ) {
    var formId = stla_checkbox_radio_current_form.formId;
	wp.customize.bind('ready', function () {
            //hide all the selection fields if no form selected
        $('body').on('click', '#accordion-panel-gf_stla_panel', function() {
            if ($('#customize-control-gf_stla_hidden_field_for_form_id').length) {
                $('#accordion-section-gf_stla_custom_checkbox').hide();
                $('#accordion-section-gf_stla_custom_radio').hide();

            }
        });

        
    // hide checkbox shpae when type is switch.
        wp.customize('gf_stla_checkbox_radio_' + formId + '[checkbox][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[checkbox][shape]', function(control) {
                var visibility = function() {
                    if ('switch' === setting.get()) {
                        control.container.slideUp(180);
                    } else {
                        control.container.slideDown(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        });

        // Show basic style when checkbox type is basic.
        wp.customize('gf_stla_checkbox_radio_' + formId + '[checkbox][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[checkbox][style]', function(control) {
                var visibility = function() {
                    if ('default' === setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        });

         // Show icon library when checkbox type is icon.
        wp.customize('gf_stla_checkbox_radio_' + formId + '[checkbox][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[checkbox][fontawesome-icon]', function(control) {
                var visibility = function() {
                    if ('icon' === setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        });

        // Show switch type when checkbox type is switch.
        wp.customize('gf_stla_checkbox_radio_' + formId + '[checkbox][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[checkbox][switch]', function(control) {
                var visibility = function() {
                    if ('switch' === setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        });



        // Show icon type when checkbox type is icon.
        wp.customize('gf_stla_checkbox_radio_' + formId + '[checkbox][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[checkbox][icon-type]', function(control) {
                var visibility = function() {
                    if ('icons' === setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        });

        // Show image when checkbox type is image.
        wp.customize('gf_stla_checkbox_radio_' + formId + '[checkbox][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[checkbox][image]', function(control) {
                var visibility = function() {
                    if ('image' === setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        })

        // hide checkbox shape when type is image
        wp.customize('gf_stla_checkbox_radio_' + formId + '[checkbox][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[checkbox][shape]', function(control) {
                var visibility = function() {
                    if ('image' === setting.get()) {
                        control.container.slideUp(180);
                    } else {
                        control.container.slideDown(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        })

        // Hide checked color on images
        wp.customize('gf_stla_checkbox_radio_' + formId + '[checkbox][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[checkbox][checked-color]', function(control) {
                var visibility = function() {
                    if ('image' === setting.get()) {
                        control.container.slideUp(180);
                    } else {
                        control.container.slideDown(180);                        
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        })

        // ----------------------------------------------- Radio Controls ----------------------------------------------------------------

         // Show icon shape when type in basic.
         wp.customize('gf_stla_checkbox_radio_' + formId + '[radio][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[radio][shape]', function(control) {
                var visibility = function() {
                    if ('switch' === setting.get()) {
                        control.container.slideUp(180);
                    } else {
                        control.container.slideDown(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        })

        // Show icon style when type in basic.
        wp.customize('gf_stla_checkbox_radio_' + formId + '[radio][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[radio][style]', function(control) {
                var visibility = function() {
                    if ('default' === setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        })
        
        // Show select icon when type in icon.
        wp.customize('gf_stla_checkbox_radio_' + formId + '[radio][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[radio][fontawesome-icon]', function(control) {
                var visibility = function() {
                    if ('icon' === setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        })
        // Show switch when type is switch
        wp.customize('gf_stla_checkbox_radio_' + formId + '[radio][type]', function(setting) {
            wp.customize.control('gf_stla_checkbox_radio_' + formId + '[radio][switch_type]', function(control) {
                var visibility = function() {
                    if ('switch' === setting.get()) {
                        control.container.slideDown(180);
                    } else {
                        control.container.slideUp(180);
                    }
                };
                visibility();
                setting.bind(visibility);
            });
        })



	});
    
    

} )( jQuery );