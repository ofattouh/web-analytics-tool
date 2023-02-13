/* global visualizer1 */
/* global Handsontable */
/* global console */
(function($, v) {

    var __dataMightHaveChanged  = false;

    $.fn.loadDataInTable    = function(cols, d, hot){
        var data        = [];
        var heading     = [];
        var type        = [];
        var i;
        for( i = 0; i < cols.length; i++){
            heading[heading.length] = cols[i].label;
            type[type.length]       = cols[i].type;
        }

        data[data.length]   = heading;
        data[data.length]   = type;
        
        for( i = 0; i < d.length; i++){
            data[data.length]   = d[i];
        }

        try{
            hot.loadData(data);
        }catch(error){
            console.log("Unable to load data in editor " + error);
        }
    };

    $.fn.submitData         = function(hot){
        if(__dataMightHaveChanged){
            $( '#editor-chart-button' ).attr("disabled", "disabled");

            __dataMightHaveChanged  = false;
            $('#chart-data').val(JSON.stringify(hot.getData()));
            $('#canvas').lock();
            if($('#csv-form').length > 0){
                $('#csv-form').submit();
            }else{
                $('#editor-form').submit();
            }
            $( '#editor-chart-button' ).removeAttr("disabled");
        }
    };

    v.render                = function(series, data){
        $.fn.loadDataInTable(series, data, hot);
        $( '#editor-chart-button' ).removeAttr("disabled");
    };

    var container   = $( '#chart-editor' ).get(0);
    var hot         = new Handsontable(
        container,
        {
            language: v.locale,
            startRows: 10,
            startCols: 10,
            minRows: 35,
            minSpareRows: 35,
            minCols: 35,
            rowHeaders: true,
            colHeaders: true,
            allowInvalid: false,
            cells: function (row, col, prop) {
                var cellProperties;
                if (row === 1) {
                    cellProperties = {
                        type: 'autocomplete',
                        source: v.types,
                        strict: true
                    };
                }
                if ( row > 1 && v.data && v.data.date_formats ) {
                    v.data.date_formats.map(function(i, index){
                        if(i.index === col){
                            cellProperties = {
                                type: 'date',
                                dateFormat: i.format,
                                correctFormat: true,
                                allowInvalid: true,
                            };
                        }
                    });
                }
                return cellProperties;
            },
            afterLoad: function (){
                $( '#editor-chart-button' ).removeAttr("disabled");
                $.fn.submitData(this);
            }
        }
    );

    if(v.data){
        $.fn.loadDataInTable(v.data.series, v.data.data, hot);
    }


    $( '#existing-chart' ).on( 'click', function(){
        $.ajax({
            url: $( '#editor-resources' ).length > 0 ? $( '#editor-resources' ).attr( 'data-ajax' ) : $(this).attr( 'data-viz-link' ),
            data: 'chart_id=' + $( '#chart-id' ).val(),
            success: function(d, textStatus, XMLHttpRequest){
                if( d.success ){
                    __dataMightHaveChanged  = true;
                    $.fn.loadDataInTable(d.series, d.data, hot);
                    $.fn.submitData(hot);
                }
            }
        });
    });

    $( '#editor-undo' ).on( 'click', function(){
        while(hot.isUndoAvailable()){
            hot.undo();
        }
    });

    $( '.visualizer-src-tab' ).on( 'click', function(){
        if($('.visualizer-editor-tab').attr('data-current') === 'editor') {
            $('.visualizer-editor-tab').attr( 'data-current', 'chart' );
            $( '#chart-editor' ).hide();
            $( '#canvas' ).css("z-index", "1");
        }
    } );

    $( 'body' ).on('visualizer:chart:edit', function(event, v){

        $('body').off('visualizer:change:action').on('visualizer:change:action', function(e){
            var button = $( '#editor-button' );
            button.val( button.attr( 'data-t-chart' ) );
            button.html( button.attr( 'data-t-chart' ) );
            button.attr( 'data-current', 'chart' );
            $('p.viz-editor-selection').show();
            $( '#chart-editor' ).hide();
            $( '#editor-undo' ).hide();
            $( '#canvas' ).css("z-index", "1");
        });

        var button = $( '#editor-button' );
        if( button.attr( 'data-current' ) === 'chart'){
            button.val( button.attr( 'data-t-editor' ) );
            button.html( button.attr( 'data-t-editor' ) );
            button.attr( 'data-current', 'editor' );
            $('p.viz-editor-selection').hide();
            $( '.visualizer-editor-lhs' ).hide();
            $( '#chart-editor' ).css("z-index", "9999").show();
            $( '#editor-undo' ).show();
            $( '#canvas' ).css("z-index", "-100");
        }else{
            __dataMightHaveChanged  = true;
            $.fn.submitData(hot);
            button.val( button.attr( 'data-t-chart' ) );
            button.html( button.attr( 'data-t-chart' ) );
            button.attr( 'data-current', 'chart' );
            $('p.viz-editor-selection').show();
            $( '#chart-editor' ).hide();
            $( '#editor-undo' ).hide();
            $( '#canvas' ).css("z-index", "1");
        }
    });

    $( '#filter-chart-button' ).on( 'click', function(){

        $('body').off('visualizer:change:action').on('visualizer:change:action', function(e){
            var filter_button = $( '#filter-chart-button' );
            filter_button.val( filter_button.attr( 'data-t-chart' ) );
            filter_button.html( filter_button.attr( 'data-t-chart' ) );
            filter_button.attr( 'data-current', 'chart' );
            $( '#filter-wizard' ).hide();
            $( '#canvas' ).css("z-index", "1").show();
        });

        if( $(this).attr( 'data-current' ) === 'chart'){
            $(this).val( $(this).attr( 'data-t-filter' ) );
            $(this).html( $(this).attr( 'data-t-filter' ) );
            $(this).attr( 'data-current', 'filter' );
            $( '.visualizer-editor-lhs' ).hide();
            $( '#filter-wizard' ).css("z-index", "9999").show();
            $( '#canvas' ).hide();
        }else{
            var filter_button = $(this);
            
            // the user wants to quit the process without providing any data.
            if($('table.filter-wizard tr').length === 2){
                filter_button.val( filter_button.attr( 'data-t-chart' ) );
                filter_button.html( filter_button.attr( 'data-t-chart' ) );
                filter_button.attr( 'data-current', 'chart' );
                $( '#filter-wizard' ).hide();
                $( '#canvas' ).css("z-index", "1").show();
                return;
            }
            
            $.ajax({
                url     : v.ajax['url'],
                method  : 'post',
                data    : {
                    'action'    : v.ajax['actions']['filter_get_data'],
                    'nonce'     : v.ajax['nonces']['filter_get_data'],
                    'props'     : $('#filter-wizard-form').serialize(),
                    'chart_id'  : $('#visualizer-chart-id').attr('data-id')
                },
                success : function(d, textStatus, XMLHttpRequest){
                    if(d.success) {
                        $('#canvas').lock();
                        $('#chart-data').val(d.data);
                        $('#chart-data-src').val(v.filter);
                        if($('#csv-form').length > 0){
                            $('#csv-form').submit();
                        }else{
                            $('#editor-form').submit();
                        }
                        filter_button.val( filter_button.attr( 'data-t-chart' ) );
                        filter_button.html( filter_button.attr( 'data-t-chart' ) );
                        filter_button.attr( 'data-current', 'chart' );
                        $( '#filter-wizard' ).hide();
                        $( '#canvas' ).css("z-index", "1").show();
                    }
                }
            });
        }
    } );

    $( '#visualizer-post-types' ).on( 'change', function(){
        var post_type   = $(this).val();
        $('#visualizer-post-fields').empty().append('<option value="">' + v.l10n['loading'] + '</option>').removeAttr('multiple').trigger('chosen:updated');
        $('tr.filter-wizard-new').remove();
        $('#filter-wizard-post-type').val(post_type);

        $.ajax({
            url     : v.ajax['url'],
            method  : 'post',
            data    : {
                'action'    : v.ajax['actions']['filter_get_props'],
                'nonce'     : v.ajax['nonces']['filter_get_props'],
                'post_type' : post_type
            },
            success : function(d, textStatus, XMLHttpRequest){
                if(d.success) {
                    $('#visualizer-post-fields').empty().attr('multiple', 'multiple');
                    $.each(d.fields, function(i, x){
                        $('#visualizer-post-fields').append('<option value="' + x + '">' + x + '</option>');
                    });
                    $('#visualizer-post-fields').trigger('chosen:updated');
                }
            }
        });
    });

    function update_filter_table(params){
        if(params.selected) {
            var tr = $('.filter-wizard-template').clone().addClass('vz-filter-row').removeClass('filter-wizard-template').addClass('filter-wizard-new').removeAttr('style').attr('id', params.selected.replace('(', '_').replace(')', '_'));
            $('table.filter-wizard').append(tr);
            tr.find('td').first().html(params.selected);
            tr.find('input').val(params.selected);
        }else{
            $('tr#' + params.deselected.replace('(', '_').replace(')', '_')).remove();
        }
    }

    $( '#settings-button' ).on( 'click', function(){
        if( $('#editor-chart-button').attr( 'data-current' ) !== 'chart' ){
            __dataMightHaveChanged  = hot.isUndoAvailable();
            $.fn.submitData(hot);
        }
    });

    $('body').on('click', '#vz-save-schedule', function(){

        var url = $('#vz-schedule-url').val();

        if (url !== '') {
            if (url.indexOf('localhost') !== -1 || /^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url)) {
                if (url.substr(url.length - 8) === '/pubhtml') {
                    url = url.substring(0, url.length - 8) + '/export?format=csv';
                }
                $('#canvas').lock();
                $(this).parent().submit();
            } else {
                window.alert(v.l10n.invalid_source);
            }
        }
    });
    function refresh_table(){
        if( $( "#filter-wizard-details tr.vz-filter-row" ).length > 0 ) {
            $("#filter-wizard-details").show();
            $("#vz-no-fields").hide();
        }
        else {
            $("#filter-wizard-details").hide();
            $("#vz-no-fields").show();
        }
    }

    // wrapper to update code mirror options
    function code_mirror_update($name, $value){
        //console.log("setting " + $name + " = " + $value);
        $('body').trigger('visualizer:db:query:changeoption', {name: $name, value: $value});
    }

    function init_db_wizard() {
        $('.db-query-additional').accordion({
            heightStyle: 'content',
            active: false,
            collapsible: true
        });

        // open the accordions by default if they are indicated with the 'open' class.
        $('.db-query-additional .viz-substep.open').each(function(i, e){
            $('.db-query-additional').accordion( "option", "active", i );
        });

        if($('.db-query-additional select').prop('selectedIndex') > 0){
            $('.db-query-additional input').show();
        }else{
            $('.db-query-additional input').hide();
        }

        // some other DB selected
        $('.db-query-additional select').on('change', function(e){
            $('.db-wizard-results').empty();
            $('.db-wizard-error').empty();

            $('body').trigger('visualizer:db:query:setvalue', {value: ''});

            code_mirror_update('mode', $('.db-query-additional select option:selected').attr('data-dialect'));

            if($(this).prop('selectedIndex') > 0){
                // disable the text area.
                code_mirror_update('readOnly', 'nocursor');
                $('body').trigger('visualizer:db:query:setvalue', {value: v.l10n.db_remote_provide});
                $('.db-query-additional input[type="text"], .db-query-additional input[type="password"]').each(function(i, input){
                    $(input).val('');
                });
                $('.db-query-additional input[type="text"], .db-query-additional input[type="password"], .db-query-additional input[type="button"]').show();
            }else{
                // enable the text area.
                code_mirror_update('readOnly', false);
                $('body').trigger('visualizer:db:query:setvalue', {value: ''});
                // reload the original table/columns
                code_mirror_update('hintOptions', { tables: v.db_query.tables });
                $('.db-query-additional input[type="text"], .db-query-additional input[type="password"]').each(function(i, input){
                    $(input).val('');
                });
                $('.db-query-additional input[type="text"], .db-query-additional input[type="password"], .db-query-additional input[type="button"]').hide();
            }
        });

        $('.db-test-conn-result').removeClass('conn-yes').removeClass('conn-no').html('');

        $('.db-test-conn').on('click', function(e){
            $('.db-test-conn-result').removeClass('conn-yes').removeClass('conn-no').html('');
            // all parameters are mandatory.
            var $submit = true;
            $('.db-query-additional input[type="text"], .db-query-additional input[type="password"]').each( function(i, input) {
                if($(input).val().length === 0){
                    $submit = false;
                    return false;
                }
            });

            if( ! $submit ){
                return false;
            }

            start_ajax($('#visualizer-db-query'));

            $.ajax({
                url: v.ajax['url'],
                method: 'POST',
                data    : {
                    'action'    : v.ajax['actions']['db_check_conn'],
                    'nonce'     : v.ajax['nonces']['db_check_conn'],
                    'data'      : $('#db-query-form').serialize()
                },
                success: function(d, textStatus, XMLHttpRequest){
                    $('.db-test-conn-result').html(d.msg);
                    // make the result message vanish after sometime
                    $('.db-test-conn-result').fadeOut(5000, function(){
                        $('.db-test-conn-result').removeClass('conn-yes').removeClass('conn-no').html('').show();
                    });
                    if( d.success ){
                        $('.db-test-conn-result').addClass('conn-yes');
                        // enable the text area.
                        code_mirror_update('readOnly', false);
                        $('body').trigger('visualizer:db:query:setvalue', {value: ''});
                        code_mirror_update('hintOptions', { tables: d.meta });
                    }else{
                        code_mirror_update('readOnly', 'nocursor');
                        $('body').trigger('visualizer:db:query:setvalue', {value: v.l10n.db_remote_provide});
                        $('.db-test-conn-result').addClass('conn-no');
                    }
                    end_ajax($('#visualizer-db-query'));
                }
            });
        });
    }

    function start_ajax(element){
        element.lock();
    }

    function end_ajax(element){
        element.unlock();
    }

    $(document).ready(function(){ 
        if($('.visualizer-remote-url') && $('.visualizer-remote-url').val().length > 0){ 
            $('.visualizer-import-url').addClass('open');
            $('.visualizer-import-url-schedule').addClass('open');
            $('.visualizer-import-url-schedule').parent().find('.viz-section-items').show();
        }

        refresh_table();

        $('.visualizer-chosen').chosen({
            width               : '50%',
            max_selected_options: v.max_selected_options,
            search_contains     : true
        });
        $('#visualizer-post-fields').on('change', function(evt, params) {
            update_filter_table(params);
            refresh_table();
        });

        init_db_wizard();
    });

})(jQuery, visualizer1);