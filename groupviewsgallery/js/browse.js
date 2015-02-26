// self executing function for namespacing code
(function( Browse, $, undefined ) {

    var loadingmessage, groupid;

    //Public Method
    Browse.filtercontent = function(limit, offset, blockinstanceid) {
        offset = typeof offset !== 'undefined' ? offset : 0;
        if (typeof this.groupid == 'undefined') {
            return;
        }
		
		var pd = {'groupid': this.groupid, 'limit': limit, 'offset': offset, 'blockinstanceid': blockinstanceid};
		
        loadingmessage.removeClass('hidden');
		
        sendjsonrequest(config['wwwroot'] + 'blocktype/groupviewsgallery/browse.json.php', pd, 'POST', function(data) {
            loadingmessage.addClass('hidden');
            $('#gallery').replaceWith(data.data.tablerows);
            $('#browselist_pagination').html(data.data.pagination);
            connect_hover_events();
        });
    };
	
    function init() {
        loadingmessage = $('#loadingmessage');
        Browse.groupid = $('#groupid').val();
        connect_hover_events();
    }
	
    function connect_hover_events() {
        $('.gall-cell').hover(function() {
            $('.gall-span', this).stop().animate({"opacity": 1});
        },function() { 
            $('.gall-span', this).stop().animate({"opacity": 0});
        });
        var pagename ='';
        $('.pagelink').hover(function() {
            pagename = $(this).text();
            $(this).text('View page');
        }, function() {
            $(this).text(pagename);
        });
    }
	
    $(document).ready(function() {
        init();
    });

}( window.Browse = window.Browse || {}, jQuery ));
