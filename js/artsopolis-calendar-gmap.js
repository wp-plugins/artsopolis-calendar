
/* Initialize search by keyword */
var delay       = 500, // Delay key up search event
    is_loading  = false, // For the delay search event
    is_dirty    = false, // For the delay search event
    not_scroll  = false, // For the scroll of artsopolis_calendar_update_page function 
    is_loaded_on_going_tab = false, // If this tab has already loaded, not load it again
    is_resetted = false; // If user click on Reset Button, when change tab we need to reset search
    
(function($) {
    $(function() {
        
        /* Gmap event detail */
        if(typeof __elisoft !== 'undefined') {
            if(__elisoft.art_calendar && __elisoft.art_calendar.latitude ) {
                artsopolis_calendar_google_map({latitude: __elisoft.art_calendar.latitude, longitude: __elisoft.art_calendar.longitude });
            }
            else if(__elisoft.art_calendar && __elisoft.art_calendar.gmap_address) {
                artsopolis_calendar_search_address_gmap(__elisoft.art_calendar.gmap_address);
            }
        }
        
    });
            
})(jQuery);

var artsopolis_calendar_map            = false,
   artsopolis_calendar_clickmarker,
   artsopolis_calendar_infowindow      = new google.maps.InfoWindow(),
   artsopolis_calendar_geocoder        = new google.maps.Geocoder(),
   artsopolis_calendar_map_container   = 'artsopolis_calendar_map_canvas';
   
function artsopolis_calendar_google_map (ops, callback) {
   var zoom        = 16,
       lat_lng     = new google.maps.LatLng(ops.latitude, ops.longitude),
       options     = {
           zoom: zoom,
           minZoom: 8,
           center: lat_lng,
       };
       
   artsopolis_calendar_map = new google.maps.Map(document.getElementById(artsopolis_calendar_map_container), options);
   artsopolis_calendar_clickmarker = new google.maps.Marker ({
           position: lat_lng,
           map: artsopolis_calendar_map,
           draggable: false
   });
   
//   var latlngbounds = new google.maps.LatLngBounds();
//   latlngbounds.extend(lat_lng);
//   artsopolis_calendar_map.fitBounds(latlngbounds);
}

//Search address of google
function artsopolis_calendar_search_address_gmap(address_name, ops, callback) {
   artsopolis_calendar_geocoder.geocode({address: address_name}, function(results, status) {

       if (status == google.maps.GeocoderStatus.OK) {
           var first_result    = results[0],
               location        = first_result.geometry.location;
			
           artsopolis_calendar_google_map({latitude: location.lat(), longitude: location.lng()});
       }
   });
}



