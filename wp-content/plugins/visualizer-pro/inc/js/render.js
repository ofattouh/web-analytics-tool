/* global visualizer2 */
/* global Handsontable */
(function($, v) {

    $(document).ready(function(){ 
        initAll();
    });

    function visualizer_perform_action(e){
        if(e.origin !== window.location.protocol + '//' + window.location.host){
            return;
        }

        switch(e.data.type){
            case 'edit':
                var hot = null;
                var chart_id = e.data.chart;
                var id = 'visualizer-editor-' + chart_id;
                var buttons = '<button class="visualizer-editor-save" data-visualizer-chart="' + chart_id + '">' + v.i10n['save'] + '</button>' + '<button class="visualizer-editor-cancel">' + v.i10n['cancel'] + '</button>';
                $('body').append($('<a id="' + id + '-anchor" href="#' + id + '-popup" ></a><div class="visualizer-editor-front-container mfp-hide" id="' + id + '-popup"><div class="visualizer-editor-front" id="' + id + '"></div><div class="visualizer-editor-front-actions">' + buttons + '</div></div></div>'));
                $('#' + id + '-anchor').magnificPopup({
                    type: 'inline',
                    modal: true
                });
                $('.visualizer-editor-cancel').on('click', function(e){
                    e.preventDefault();
                    $.magnificPopup.close();
                    deleteItems(id);
                });
                $('.visualizer-editor-save').on('click', function(e){
                    e.preventDefault();
                    $.ajax({
                        url     : v.rest_url.replace('#id#', chart_id).replace('#type#', 'save'),
                        method  : 'post',
                        beforeSend: function ( xhr ) {
                            xhr.setRequestHeader( 'X-WP-Nonce', v.wp_nonce );
                        },
                        data    : {
                            data    : JSON.stringify(hot.getData()),
                        },
                        success: function(){
                            $.magnificPopup.close();
                            deleteItems(id);
                            window.location.reload();
                        }
                    });
                });
                $('#' + id + '-anchor').trigger('click');
                hot = loadEditor(id, chart_id, e.data.data);
                break;
        }
    }

    function deleteItems(id){
        $('#' + id).remove();
        $('#' + id + '-popup').remove();
    }

    function loadEditor(id, chart, data){
        var container   = $( '#' + id ).get(0);
        var hot         = new Handsontable(
            container,
            {
                language: v.locale,
                minRows: 30,
                minCols: 26,
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
                    if ( row > 1 && data.date_formats ) {
                        data.date_formats.map(function(i, index){
                            if(i.index === col){
                                cellProperties = {
                                    type: 'date',
                                    dateFormat: i.format,
                                    correctFormat: true,
                                };
                            }
                        });
                    }
                    return cellProperties;
                },
                afterLoad: function (){
                    $( '#editor-chart-button' ).removeAttr("disabled");
                }
            }
        );

        loadDataInTable(data.series, data.data, hot);
        return hot;
    }

    function loadDataInTable(cols, d, hot){
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

        hot.loadData(data);
    }

    function initAll(){
        window.addEventListener("message", visualizer_perform_action, false);
    }

})(jQuery, visualizer2);

function visualizer_perform_action(type, chart, data){
    window.postMessage({'type': type, 'chart': chart, 'data': data}, window.location.protocol + '//' + window.location.host);
}
