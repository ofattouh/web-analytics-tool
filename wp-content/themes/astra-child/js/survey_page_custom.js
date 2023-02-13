//----------------------------------------------------------------------------------------
// Garvity Forms Custom JS Script

jQuery(function($){
    /*
    $('.gform_wrapper form').on('submit', function(e){
        // document.getElementById("form-submit-indicator-div").style.display = "block";
        $('#form-submit-indicator-div').show();
    });
    */

    // Only trigger with submit button
    $('.gform_wrapper form input[type=submit]').on('click', function(e){
        $('#form-submit-indicator-div').show();
    });
});
