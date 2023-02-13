//----------------------------------------------------------------------------------------
// Garvity Forms Custom JS Script

jQuery(function($){
    // OSIR organization report start date & end date
    $('.date_picker').datepicker({
        dateFormat : 'yy-mm-dd',    // https://jqueryui.com/datepicker/#date-formats
        // minDate: new Date(2021, 7 - 1, 10), // 2021-07-10
        maxDate: new Date(), // https://api.jqueryui.com/datepicker/#option-minDate
        changeMonth: true,
        changeYear: true,
        buttonImageOnly: true,
        buttonImage: '/wp-content/themes/astra-child/images/calendar-icon.gif',
        showOn: 'both', // open on focus or a click on an button
        // showOn: 'button',
        // showWeek: true,
        // yearRange: '2021:2025',     // https://jqueryui.com/datepicker/#min-max
        // yearRange: '-10:+10'     // Current Year -10 to Current Year + 10.
        // yearRange: '+0:+10'      // Current Year to Current Year + 10.
        // yearRange: '1900:+0'     // Year 1900 to Current Year.
        // yearRange: '1985:2025'   // Year 1985 to Year 2025.
        // yearRange: '-0:+0'       // Only Current Year.
        // yearRange: '2025'        // Only Year 2025.
    });

    // Validate corresponding organization report start & end Dates
    $('.org-report-form').on('submit', function(e){ 
        var clickedSubmitBtn = $("input[type=submit][clicked=true]");
        var reportSubmitID = clickedSubmitBtn.attr("report-parent-account-user-id");
        var startDateId = '#org_report_start_date_' + reportSubmitID;
        var endDateId = '#org_report_end_date_' + reportSubmitID;
        var startDate = $(startDateId).val();
        var endDate = $(endDateId).val();

        // console.log($("input[type=submit][clicked=true]"));
        // console.log(clickedSubmitBtn);
        // console.log(startDateId);
        // console.log(endDateId);
        // console.log($(startDateId));
        // console.log($(endDateId));
        // console.log(Date.parse(startDate));
        // console.log(Date.parse(endDate));

        // console.log(reportSubmitID);
        // console.log(startDate);
        // console.log(endDate);

        if ( startDate === '' || endDate === '' ) {
            alert ('Error: Organization reporting period is invalid. Please choose both the start and end dates');
            resetFormSubmitInput();
            return false;
        } else if ( Date.parse(startDate) > Date.parse(endDate) ) {
            alert ('Error: Organization reporting period is invalid. Start date should be equal to or after the end date');
            resetFormSubmitInput();
            return false;
        }

        return true;
    });
    
    // Determine which Form submit button was clicked and save the organization report parent user id
    $(".org-report-form input[type=submit]").click(function() {
        var btnId = $(this).attr('id').split('-');
        $(this).attr('clicked', 'true');
        $(this).attr('report-parent-account-user-id',  btnId[1]);
    });

    // reset all reports forms clicked submit buttons
    function resetFormSubmitInput() {
        $('.org-report-form').children('input[type=submit]').each(function () {
            // console.log($(this));
            // $("input[type=submit]", $(this).parents(".org-report-form")).removeAttr("clicked");
            $(this).attr('clicked', 'false');
        });
    }
});
