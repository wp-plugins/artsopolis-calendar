(function ($) {
    $(function () {
        // Loads the color pickers
	$('.nav-bg-color').wpColorPicker();
        
        // Handle the fancy box for the logo
        $("a#ac-teaser-widget-logo-link").fancybox({});
        $("a#ac-plugin-logo-link").fancybox({});
        
        // Upload logo
        artsopolis_calendar_upload();
        
        // Add sorting for featured events use jQuery Datatable
        add_sorting_data_table();
        
        $('#artsopolis-calendar-settings-form').submit(check_artsopolis_calendar_form);
        $('#ac-show-list-territory').click(show_territories);
        $('input[name="artsopolis_calendar_options[feed_url]"]').keyup(function (e) {
            // Not ctrl v, ctrl a, f5, tab, ctrl c 
            if (e.keyCode != 17 && e.keyCode != 65 && e.keyCode != 67 && e.keyCode != 9 && e.keyCode != 116) { 
                clearTimeout($.data(this, 'timer'));
                if (e.keyCode == 13) {
                    artsopolis_calendar_check_feed_url();
                }
                else
                    $(this).data('timer', setTimeout(artsopolis_calendar_check_feed_url, 500));
                
            }
        });
        $('input[name="artsopolis_calendar_options[feed_url]"]').focus(artsopolis_calendar_check_feed_url_empty);
        $('.artsopolis-calendar-settings-category .hide-show-subcategory').bind('click', hide_show_subcategory);
        $('input[name="artsopolis_calendar_options[category_xml_feed_url]"]').keyup(function (e) {
            
            // Not ctrl v, ctrl a, f5, tab, ctrl c
            if (e.keyCode != 17 && e.keyCode != 65 && e.keyCode != 67 && e.keyCode != 9 && e.keyCode != 116) { 
                clearTimeout($.data(this, 'timer'));
                if (e.keyCode == 13) {
                    artsopolis_calendar_check_category_xml_feed();
                }
                else
                    $(this).data('timer', setTimeout(artsopolis_calendar_check_category_xml_feed, 500));
            }
        });
        
        $('#arts-delete-plugin-logo').bind('click', artsopolis_calendar_delete_image_by_url);
        $('#arts-delete-teaser-logo').bind('click', artsopolis_calendar_delete_image_by_url);
        });
    
    function check_artsopolis_calendar_form() {
        var messages    = '',
            main_focus  = $('input[name="artsopolis_calendar_options[feed_url]"]');
   
        if ($('input[name="artsopolis_calendar_options[feed_valid]"]').val() == "0") {
            messages += '\n + The XML feed is invalid';
            messages = "Warning:\n" + messages + "\n\n  Do you want to continue ?";
        } else {
            return true;
        }
        
        if(messages && confirm(messages)) {
            return true;
        } 
        
        if(main_focus){
            main_focus.focus();
        }
        
        return false;
    }


    function show_territories() {
        var url = artsopolis_calendar_obj.admin_url + '/?action=ac_get_territories'
        tb_show("Territories List", url);
        return false;
    }
    
    function artsopolis_calendar_check_feed_url(callback) {
        var feed_url = $('input[name="artsopolis_calendar_options[feed_url]"]').val();
        
        if (feed_url.length < 3 || ! feed_url.length) {
            $('#checking-xml-feed').hide();
            
            $('.xml-feed .artsopolis-calendar-error').show();
            $('.xml-feed .artsopolis-calendar-success').hide();
            $('input[name="artsopolis_calendar_options[feed_valid]"]').val(0);
            artsopolis_calendar_hide_loading();
            return false;
        }
        
        $('.xml-feed .artsopolis-calendar-success').hide();
        $('.xml-feed .artsopolis-calendar-error').hide();
        $('#checking-xml-feed').show();
        artsopolis_calendar_show_loading();
        $('#artsopolis-calendar-settings-form #submit').prop('disabled', true);

        $.ajax({
            url: artsopolis_calendar_obj.admin_url,
            type: 'post',
            data: {
                'action': 'ac_check_valid_feed_url',
                'feed_url': encodeURI(feed_url)
            },
            beforeSend: function() {
                $('#checking-xml-feed').attr('is-checking', 1);
            },
            success: function(res) {
                var parent = $('.artsopolis-calendar-status-container'),
                    success = true;
                $('#checking-xml-feed').attr('is-checking', 0);    
                if (parseInt(res)) {
                    $('.xml-feed .artsopolis-calendar-error').hide();
                    $('.xml-feed .artsopolis-calendar-success').show();
                    $('input[name="artsopolis_calendar_options[feed_valid]"]').val(1);
                } else {
                    $('.xml-feed .artsopolis-calendar-error').show();
                    $('.xml-feed .artsopolis-calendar-success').hide();
                    $('input[name="artsopolis_calendar_options[feed_valid]"]').val(0);
                    success = false;
                }

                if ($('input[name=feed_hidden]').val() != feed_url) {
                    $('input[name="artsopolis_calendar_options[has_changed]"]').val(1);
                } else {
                    $('input[name="artsopolis_calendar_options[has_changed]"]').val(0);
                }
                
                if (typeof(callback) === 'function') {
                    callback({
                        success: success
                    });
                }

                parent.show();
                artsopolis_calendar_hide_loading();
                
                if (! parseInt($('#checking-category-xml').attr('is-checking'))) {
                    $('#artsopolis-calendar-settings-form #submit').prop('disabled', false);
                }
                
                $('#checking-xml-feed').hide();
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    }
    
    
    function artsopolis_calendar_check_category_xml_feed(callback) {
        var url = $('input[name="artsopolis_calendar_options[category_xml_feed_url]"]').val();

        if (! url.length) {
            $('.xml-category .artsopolis-calendar-error').show();
            $('#wrapper-category').html('');
            
            $('.xml-category .artsopolis-calendar-success').hide();
            $('input[name="artsopolis_calendar_options[category_valid]"]').val(0);
            return false;
        }
        
        $.ajax({
            url: artsopolis_calendar_obj.admin_url,
            type: 'post',
            data: {
                'action': 'ac_check_valid_category_xml_url',
                'category_xml_feed_url': encodeURI(url)
            },
            beforeSend: function() {
                $('#checking-category-xml').attr('is-checking', 1);
                $('.xml-category .artsopolis-calendar-success').hide();
                $('.xml-category .artsopolis-calendar-error').hide();
                $('#checking-category-xml').show();
                artsopolis_calendar_show_loading();
                $('#artsopolis-calendar-settings-form #submit').prop('disabled', true);
            },
            success: function(res) {
                var parent = $('.artsopolis-calendar-status-container');
                $('#checking-category-xml').attr('is-checking', 0);
                $('#wrapper-category').html(res);    
                if (res) {
                    $('.xml-category .artsopolis-calendar-error').hide();
                    $('.xml-category .artsopolis-calendar-success').show();
                    $('input[name="artsopolis_calendar_options[category_valid]"]').val(1);
                } else {
                    $('.xml-category .artsopolis-calendar-error').show();
                    $('.xml-category .artsopolis-calendar-success').hide();
                    $('input[name="artsopolis_calendar_options[category_valid]"]').val(0);
                }

                parent.show();
                artsopolis_calendar_hide_loading();
                
                if (! parseInt($('#checking-xml-feed').attr('is-checking'))) {
                    $('#artsopolis-calendar-settings-form #submit').prop('disabled', false);
                }
                
                $('#checking-category-xml').hide();
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    }

    function artsopolis_calendar_show_loading() {
        if(document.getElementById("TB_overlay") === null) {
            jQuery("body").append("<div id='TB_overlay'></div><div id='TB_window'></div>");
            jQuery("#TB_overlay").addClass("TB_overlayBG");//use background and opacity
        }
        console.log(imgLoader.src);
        jQuery("body").append("<div id='TB_load'><img src='"+imgLoader.src+"' width='208' /></div>");//add loader to the page
        jQuery('#TB_load').show();//show loader
        jQuery('#TB_load').show();

        jQuery("#TB_overlay").click(tb_remove);
    };

    function artsopolis_calendar_hide_loading() {
        tb_remove();
        jQuery("#TB_load").remove();
    };
    
    function hide_show_subcategory() {
        var elm = $(this).parent().next();
        elm.toggleClass('hidden');
        
        $(this).html(elm.hasClass('hidden') ? '&#9660;' : '&#9658;');
    }
    
    function artsopolis_calendar_check_feed_url_empty() {
        if (! $(this).val()) {
            $('input[name="artsopolis_calendar_options[feed_valid]"]').val(0);
            $('input[name="artsopolis_calendar_options[has_changed]"]').val(1);
        }
    }
    
    function add_sorting_data_table() {
        var selected_event_obj = $('#artsopolis-calendar-selected-events'),
            selected_event = selected_event_obj.val();
        
        if ( ! selected_event_obj.length ) {
            return false;
        }
        
        if (selected_event == undefined) {
            selected_event = '';
        }
        
        selected_event = selected_event.split(',');
        
        /* Paging list */
        $("#artsopolis-calendar-featured-events").dataTable({
            "aaSorting": [[ 4, "desc" ]],
            "fnDrawCallback": function() {
                // in case your overlay needs to be put away automatically you can put it here
                $('#artsopolis-calendar-featured-events').css('visibility', 'inherit');
                $('#processing-artsopolis-calendar').hide();
            },
            "fnCreatedRow": function ( row, data, index ) {
                
                if ( row.childNodes[1] != undefined ) {
                    
                    var checkbox = row.childNodes[1].children[0],
                        event_id = checkbox.value;
                   
                    if ( jQuery.inArray(event_id.toString(), selected_event) != -1 ) {
                        jQuery(checkbox).attr('checked', true);
                    }
                }
                
            }
        });
    }
    
    function artsopolis_calendar_delete_image_by_url() {
        
        var image       = $(this),
            image_url   = image.attr('image-url');
        
        if (! image_url) {
            return false;
        }
        
        if(confirm('Are you sure ?')) {
            
            $.ajax({
                url: artsopolis_calendar_obj.admin_url,
                type: 'post',
                data: {
                    'action': 'ac_delete_image_by_url',
                    'image_url': image_url,
                    'opt_name': image.attr('opt-name')
                },
                beforeSend: function() {
                    artsopolis_calendar_show_loading();
                    image.hide();
                    image.next('.logo-deleting').show();
                    image.next('.logo-deleting').children().children().hide();
                    image.next().next().children().children().hide();
                },
                success: function(res) {
                    image.prev('input').prev('input').val('');
                    image.addClass('hidden');
                    image.next('.logo-deleting').hide();
                    artsopolis_calendar_hide_loading();
                },
                error: function(errorThrown) {
                    
                }
            });
        }
    }
    
    function artsopolis_calendar_upload () {
        var formfield, remove_btn, thumb;
 
        /* user clicks button on custom field, runs below code that opens new window */
        jQuery('.arts-upload-button').click(function() {
            formfield   = jQuery(this).prev('input'); //The input field that will hold the uploaded file url
            remove_btn  = jQuery(this).next('input'); 
            thumb       = jQuery(this).next('input').next('span').next('div').children().children(); 
            tb_show('','media-upload.php?TB_iframe=true');

            return false;

        });
        /*
        Please keep these line to use this code snipet in your project
        Developed by oneTarek http://onetarek.com
        */
        //adding my custom function with Thick box close function tb_close() .
        window.old_tb_remove = window.tb_remove;
        window.tb_remove = function() {
            window.old_tb_remove(); // calls the tb_remove() of the Thickbox plugin
            formfield=null;
        };

        // user inserts file into post. only run custom if user started process using the above process
        // window.send_to_editor(html) is how wp would normally handle the received data

        window.original_send_to_editor = window.send_to_editor;
        window.send_to_editor = function(html){
            
            if (formfield) {
                
                fileurl = jQuery('img',html).attr('src');
                jQuery(formfield).val(fileurl);
                jQuery(remove_btn).attr('image-url', fileurl);
                remove_btn.removeClass('hidden');
                remove_btn.show();
                remove_btn.attr('disabled', false);
                jQuery(thumb).show();
                jQuery(thumb).attr('src', fileurl);
                jQuery(thumb).parent().attr('href', fileurl);
                tb_remove();
            } else {
                window.original_send_to_editor(html);
            }
        };
    }
    
}) (jQuery);
