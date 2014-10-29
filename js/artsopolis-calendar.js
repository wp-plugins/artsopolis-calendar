/* Initialize search by keyword */
var delay       = 500, // Delay key up search event
    is_loading  = false, // For the delay search event
    is_dirty    = false, // For the delay search event
    not_scroll  = false, // For the scroll of artsopolis_calendar_update_page function 
    is_loaded_on_going_tab = false, // If this tab has already loaded, not load it again
    is_resetted = false; // If user click on Reset Button, when change tab we need to reset search
    
(function($) {
    $(function() {
        // Set today
        var today       = new Date(),
            y_today     = today.getFullYear(),
            _m_today    = today.getMonth() + 1,
            m_today     = _m_today < 10 ? '0'+ _m_today : _m_today ,
            d_today     = parseInt(today.getDate()) < 10 ? '0'+ today.getDate() : today.getDate();
            
        $('#artsopolis-calendar-choice-today').attr('data-date-from', y_today +'-'+ m_today+'-'+ d_today);
        $('#artsopolis-calendar-choice-today').attr('data-date-to', y_today +'-'+ m_today+'-'+ d_today);
        
        // Set tomorrow
        var currentDate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000),
            d_tm        = currentDate.getDate() < 10 ? '0'+ currentDate.getDate() : currentDate.getDate(),
            _m_tm       = currentDate.getMonth() + 1,
            m_tm        = _m_tm < 10 ? '0'+ _m_tm : _m_tm,
            y_tm        = currentDate.getFullYear();
        
        $('#artsopolis-calendar-choice-tomorrow').attr('data-date-from', y_tm +'-'+ m_tm +'-'+d_tm);
        $('#artsopolis-calendar-choice-tomorrow').attr('data-date-to', y_tm +'-'+ m_tm +'-'+d_tm);
       
        // Set this weekend
        var sun_of_week = today.getDay() == 0 ? today : new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay() + 7),
            d_sun       = sun_of_week.getDate() < 10 ? '0'+ sun_of_week.getDate() : sun_of_week.getDate(),
            _m_sun      = sun_of_week.getMonth() + 1,
            m_sun       = _m_sun < 10 ? '0'+ _m_sun : _m_sun,
            y_sun       = sun_of_week.getFullYear(),
            sat_of_week = today.getDay() == 0 ? new Date(new Date().getTime() - 24 * 60 * 60 * 1000) : 
                    new Date(today.getFullYear(), today.getMonth(), today.getDate() - today.getDay() + 6),
            d_sat       = sat_of_week.getDate() < 10 ? '0'+ sat_of_week.getDate() : sat_of_week.getDate(),
            _m_sat      = sat_of_week.getMonth() + 1,
            m_sat       = _m_sat < 10 ? '0'+ _m_sat : _m_sat,
            y_sat       = sat_of_week.getFullYear();
        
        
        $('#artsopolis-calendar-choice-weekend').attr('data-date-from', y_sat +'-'+ m_sat +'-'+ d_sat);    
        $('#artsopolis-calendar-choice-weekend').attr('data-date-to', y_sun +'-'+ m_sun +'-'+ d_sun);    
        
        $('#current-upcoming').on( 'click', '.artsopolis-calendar-search-again', reset_search);
        $('#ongoing-container').on( 'click', '.artsopolis-calendar-search-again', reset_search);
        
        $('#artsopolis-calendar-filter #reset').on('click', function() {
            reset_search();
            is_resetted = true;
        });
        
        /* Initialize date time picker*/
        $("#artsopolis-calendar-filter-from-date").datepicker({
            dateFormat: 'yy-mm-dd',
            showOn: 'button', 
            buttonImage: artsopolis_calendar_obj.calendar_src, 
            buttonImageOnly: true
        });

        $("#artsopolis-calendar-filter-to-date").datepicker({
            dateFormat: 'yy-mm-dd',
            showOn: 'button', 
            buttonImage: artsopolis_calendar_obj.calendar_src, 
            buttonImageOnly: true
        });
        /********************************************************
        
        /* PAGINATION */
        if (typeof(artsopolis_calendar_paging) !== 'undefined') {
            show_artsopolis_calendar_paging();
        }
        /********************************************************/
        
        
        /* Expand and collapse dates*/
        $( '.artsopolis-calendar-frontend' ).on('click', '.eli_expand-more-dates',artsopolis_calendar_show_more_dates);
        $( '.artsopolis-calendar-frontend' ).on('click', '.eli_collapse-more-dates',artsopolis_calendar_show_more_dates);
        /********************************************************/
        
        /* FILTER */
        $(".artsopolis-calendar-frontend .eli_content-can-filter-inner .eli_filter select.eli_select").bind('change', function() {
            not_scroll = true;
            artsopolis_calendar_update_page(1, 'yes');
        });
        
        $('#artsopolis-calendar-filter-from-date').bind('change', select_filter_from_date);
        
        $('#artsopolis-calendar-filter-to-date').bind('change', select_filter_to_date);
        
        $('#artsopolis-calendar-filter #keyword').keyup(function(){
            is_dirty = true;
            not_scroll = true;
            artsopolis_calendar_reload_search();
        });
        
        $('#artsopolis-calendar-choice-today').bind('click', artsopolis_calendar_filter_choice_time);
        $('#artsopolis-calendar-choice-tomorrow').bind('click', artsopolis_calendar_filter_choice_time);
        $('#artsopolis-calendar-choice-weekend').bind('click', artsopolis_calendar_filter_choice_time);
        /********************************************************/
        
        /* Expend collapse summary */
        $( '.artsopolis-calendar-frontend' ).on('click', '.eli_expand-summary', artsopolis_calendar_show_hide_summary);
        $( '.artsopolis-calendar-frontend' ).on('click', '.eli_less-summary', artsopolis_calendar_show_hide_summary);
        /********************************************************/
        
        /* Handle click on the tags */
        $( '#artsopolis-calendar-list-feed' ).on('click', '.eli_tags a.eli_a', active_filter_tags);
        $( '#ongoing-container' ).on('click', '.eli_tags a.eli_a', active_filter_tags);
        /********************************************************/
        
        /* Get data of second tab */
        $( '.eli_content-inner .tabs .tab-links #ongoing-tab' ).on( 'click', function () {
            
            // Only load data for second tab in the first time
            if ( ! is_loaded_on_going_tab || check_has_filter() ) { 

                artsopolis_calendar_update_page( 1, 'yes' );
                
                if ( ! is_loaded_on_going_tab ) is_loaded_on_going_tab = true;
                
            } 
        });
        
        // Get data on first tab clickable
        if ( $( '.eli_content-inner .tabs li' ).first().on('click', function() {
            if ( check_has_filter() ) {
                artsopolis_calendar_update_page( 1, 'yes' );
                
            }
        }))
        
        /* Handle tabs */
        $( '.eli_content-inner .tabs .tab-links a' ).on( 'click', function (e) {
            var currentAttrValue = $(this).attr('href');

            // Show/Hide Tabs
            $('.tabs ' + currentAttrValue).show().siblings().hide();

            // Change/remove current tab to active
            $(this).parent('li').addClass('active').siblings().removeClass('active');

            e.preventDefault();
        });
        
    });
    
    
    /*
     * This function will be called when you click on pagination
     **/
    function show_artsopolis_calendar_paging () {
        
        var is_first_tab   = $('.eli_content-inner .tabs .tab-links').children().first().hasClass( 'active' ),
            paging = is_first_tab ? $('#artsopolis-calendar-pagination') : $('#artsopolis-calendar-pagination-second-tab'),
            pagination = is_first_tab ? artsopolis_calendar_paging : artsopolis_calendar_paging_second_tab;
        
        if(! pagination.total_event) {
            paging.addClass('eli_hidden');
            return false;
        }
        
        paging.removeClass('eli_hidden');
        paging.pagination({
            items:          pagination.total_event,
            itemsOnPage:    pagination.page_size,
            cssStyle:       'light-theme',
            displayedPages: 3,
            edges:          1,
            onPageClick : function(pageNumber, event) {
                not_scroll = false;
                artsopolis_calendar_update_page(pageNumber, 'no', 
                $('#artsopolis-calendar-choice-weekend').hasClass('eli_active'));
            }
        });
    }
    
    /*
     * This function will be called when you click on the more or less summary
     **/
    function artsopolis_calendar_show_hide_summary() {
        var $this = $(this),
            parent_row = $this.parents('div.eli_summary'),
            sum_full = parent_row.find('div.eli_summary-full'),
            sum_short = parent_row.find('div.eli_summary-short');
            
        if ($this.hasClass('eli_expand-summary')) {
            sum_full.removeClass('eli_hidden')
            sum_short.addClass('eli_hidden');
            return;
        }     
        
        sum_full.addClass('eli_hidden')
        sum_short.removeClass('eli_hidden');
        
    }
    
    /**
     * This function will be got the data when you filter or click on the pagination
     * */
    function artsopolis_calendar_update_page(page, repagination, this_weekend, no_reset_category) {
        // Reset the tags-category val if click on the filter box
        if (no_reset_category == undefined) {
            $('#tags-category').val('');
        }
        
        // Check has this weekend
        if ((this_weekend == undefined || ! this_weekend) && $('#artsopolis-calendar-choice-weekend').hasClass('eli_active')) {
            this_weekend = 1;
        }
        
        /* Get condition for filter */
         var from_date   = $("#artsopolis-calendar-filter-from-date").val(),
             to_date     = $("#artsopolis-calendar-filter-to-date").val(),
             location    = $("#filter-by-location").val(),
             keyword     = $("#keyword").val(),
             category    = $('#filter-by-category').val(),
             tags_category  = $('#tags-category').val(),
             category_list  = $('#category-list').val(), // Click on the tags category
             is_first_tab   = jQuery('.eli_content-inner .tabs .tab-links').children().first().hasClass( 'active' ),
             container_row = is_first_tab ? $("#artsopolis-calendar-list-feed") : $(".tabs .tab-content #ongoing #ongoing-container"); 
        
        // It mean you click on the tags category
        if (tags_category) {
            console.log(tags_category, category_list.split(','))
            if ($.inArray(tags_category, category_list.split(',')) != -1) {
                category = tags_category+ '[+]'+ $('#tags-category').attr('category-name');
                $('#filter-by-category').val(category);
            } else {
                category = '-1';
                $('#filter-by-category').val('')
            }
        }     
         
        
        to_date     = to_date   === undefined ? '' : to_date;
        from_date   = from_date === undefined ? '' : from_date;
        location    = location  === undefined ? '' : location;
        keyword     = keyword === undefined ? '' : keyword; 
        category    = category === undefined ? '' : category;   
        
        /* ajax call */
         $.ajax({
             url: artsopolis_calendar_obj.admin_url,
             data: {
                 action: 'ac_get_feed',
                 page: page,
                 from_date: from_date,
                 to_date: to_date,
                 location: location,
                 repagination: repagination,
                 keyword: keyword,
                 this_weekend: typeof(this_weekend) != 'undefined' && this_weekend == true ? 1:0,
                 category: category,
                 first_tab: is_first_tab
             },
             beforeSend: function() {
                    artsopolis_calendar_show_loading( container_row );

             },
             dataType: 'html',
             success: function(data) {
                 
                 if ( $( '#artsopolis-calendar-detail-event' ) ) {
                     $( '#artsopolis-calendar-tabs-events' ).removeClass('eli_hidden');
                     $( '#artsopolis-calendar-detail-event' ).hide();
                 }
                 
                 artsopolis_calendar_hide_loading();
                 /* This outputs the result of the ajax request */
                 data = $.parseJSON( data );
                 container_row.html(data.html);
                 
                 if ( ! is_first_tab ) {
                     artsopolis_calendar_paging_second_tab.total_event = data.total;
                     artsopolis_calendar_paging_second_tab.page_size = data.page_size;
                 } else {
                     artsopolis_calendar_paging.total_event = data.total;
                     artsopolis_calendar_paging.page_size = data.page_size;
                 }
                 
                 if(repagination ==='yes') {
                     show_artsopolis_calendar_paging();
                 }

                 if (not_scroll) {
                     return false;
                 }

                 /* scroll to top */
                 $("html, body").animate({ scrollTop:  $(".artsopolis-calendar-frontend .eli_content-can-filter").offset().top }, 'slow');

             },
             error: function(errorThrown) {
                 console.log(errorThrown);
             }
         }); /*// End ajax*/
     }
        
    function artsopolis_calendar_reload_search() {
        if(! is_loading){
            var q = $('#artsopolis-calendar-filter #keyword').val();
            
            if (q.length < 3 && q.length != 0) {
                return false;
            }
            is_loading = true;
            
            // Call function to search 
            artsopolis_calendar_update_page(1, 'yes');

            // enforce the delay
            setTimeout(function(){
                is_loading=false;
                if(is_dirty){
                    is_dirty = false;
                    artsopolis_calendar_reload_search();
                }
            }, delay);
        }
    };
    
    /**
     * This function will be called when click on the more date or less date
     * */
    function artsopolis_calendar_show_more_dates() {
        not_scroll = true;
        var $this = $(this);
        $this.addClass('eli_hidden');
        if ($this.hasClass('eli_expand-more-dates')) {
            $this.next('.eli_more-date').removeClass('eli_hidden');
            $this.next('.eli_more-date').children().removeClass('eli_hidden');
        } else {
            $this.parent().prev('.eli_expand-more-dates').removeClass('eli_hidden');
            $this.parent().addClass('eli_hidden');
        }
    }
    
    /**
     * This function will be called when click on the time picker
     * */
    function artsopolis_calendar_filter_choice_time () {
        not_scroll = true;
        var $this = $(this);
        $('.eli_ac-btn-filter').removeClass('eli_active');
        $this.addClass('eli_active')
        
        $("#artsopolis-calendar-filter-from-date").val($this.attr('data-date-from'));
        $("#artsopolis-calendar-filter-to-date").val($this.attr('data-date-to'));
        artsopolis_calendar_update_page(1, 'yes', true);
    }
    
    function artsopolis_calendar_show_loading($jmain_element) {
            var $r_left = 0,
                $r_top = 0,
                $r_width = 0,
                $r_height = 0,

            /* caculate position to show loading */
                $screen_width = $(window).width(),
                $screen_height = $(window).height(),
            
                $c_left =$jmain_element.position().left,
                $c_width = $jmain_element.outerWidth(),
            
                $c_top = $jmain_element.position().top,
                $c_height = $jmain_element.outerHeight(),

                $screen_top_on_d = document.documentElement.scrollTop || document.body.scrollTop,
                $screen_left_on_d = document.documentElement.scrollLeft || document.body.scrollLeft;

            $r_left = $c_left;
            $r_top = $screen_top_on_d - $jmain_element.offset().top
            $r_width = $c_width;
            
            $r_height = $c_height;

            if($screen_top_on_d < $c_top && $screen_top_on_d + $screen_width > $c_top && $screen_top_on_d + $screen_width < $c_top + $c_height) {
              $r_height = $screen_top_on_d + $screen_height - $jmain_element.offset().top;
            }
            else if($screen_top_on_d >= $c_top && $screen_top_on_d + $screen_width >= $c_top && $screen_top_on_d + $screen_width <= $c_top + $c_height) {
              $r_height = $screen_height;
            }
            else if($screen_top_on_d >= $c_top && $screen_top_on_d + $screen_width >= $c_top && $screen_top_on_d + $screen_width > $c_top + $c_height) {
              $r_height = $jmain_element.offset().top + $c_height - $screen_top_on_d;
            }
            
            $jmain_element.append('<div class="artsopolis-calendar-loading" style="left: '+$r_left+'px; top : '+$r_top+'px; width : '+$r_width+'px; height : '+$r_height+'px"></div>');;
        }
    
        function artsopolis_calendar_hide_loading() {
            $(".artsopolis-calendar-loading").addClass('eli_hidden');
        }
        
        function select_filter_to_date() {
            remove_filter_class_time_button();
            
            var from = $('#artsopolis-calendar-filter-from-date'),
                to_val = $(this).val().replace('-', '').replace('-', ''),
                from_val = from.val().replace('-', '').replace('-', '');
            
            if (parseInt(to_val) < parseInt(from_val)) {
                from.val($(this).val());
            }
            
            artsopolis_calendar_update_page(1, 'yes');
        }
        
        function select_filter_from_date() {
            remove_filter_class_time_button();
            
            var to = $('#artsopolis-calendar-filter-to-date'),
                from_val = $(this).val().replace('-', '').replace('-', ''),
                to_val = to.val().replace('-', '').replace('-', '');
            
            if (to_val === "" || parseInt(to_val) < parseInt(from_val)) {
                to.val($(this).val());
            }
            
            artsopolis_calendar_update_page(1, 'yes');
        }
        
        function active_filter_tags() {
            var category = $(this).attr('category');
            $('#tags-category').val(category);
            $('#tags-category').attr('category-name', $(this).attr('category-name'));
            artsopolis_calendar_update_page(1, 'yes', '', 1);
        }
        
        function remove_filter_class_time_button () {
            $('#artsopolis-calendar-choice-weekend').removeClass('eli_active');
            $('#artsopolis-calendar-choice-tomorrow').removeClass('eli_active');
            $('#artsopolis-calendar-choice-today').removeClass('eli_active');
        }
        
        function reset_search() {
            $('#artsopolis-calendar-filter #keyword').val('');
            $('#artsopolis-calendar-filter #filter-by-category').val('');
            $('#artsopolis-calendar-filter #filter-by-location').val('');
            $('#artsopolis-calendar-filter #artsopolis-calendar-filter-from-date').val('');
            $('#artsopolis-calendar-filter #artsopolis-calendar-filter-to-date').val('');
            $('#artsopolis-calendar-filter #artsopolis-calendar-choice-today').removeClass('eli_active');
            $('#artsopolis-calendar-filter #artsopolis-calendar-choice-tomorrow').removeClass('eli_active');
            $('#artsopolis-calendar-filter #artsopolis-calendar-choice-weekend').removeClass('eli_active');
            $('#artsopolis-calendar-filter #filter-by-category').trigger('change');
        };

        function check_has_filter() {
             var _is_resetted = is_resetted;   
             is_resetted = false;
             return $("#artsopolis-calendar-filter-from-date").val() 
                     || $("#artsopolis-calendar-filter-to-date").val()
                     || $("#filter-by-location").val()
                     || $("#keyword").val()
                     || $('#filter-by-category').val()
                     || $('#tags-category').val() || _is_resetted;
        }
            
})(jQuery);


