
<?php

/**
 * This file is part of the Artsopolis Calendar Plugin.
 *
 * (c) vulh@elinext.com <http://elinext.com>
 *
 * This source file is subject to the elinext.com license that is bundled
 * with this source code in the file LICENSE.
 */

 $event_img = self::get_event_url($event->eventImage);
 $gmap_address = urlencode($event->venueAddress1).
                ', '.$event->venueCity. ', '.$event->venueState. ' ' . $event->venueZip;
 
 $url_ext_sign = ! get_option('permalink_structure') ? '&' : '?';
 $parent_link = get_site_url().'/'. $ac_options['calendar_slug']; 
 $link = $parent_link. $url_ext_sign .'event_id='. $event->eventID;
?>
<?php if (! empty($event)) { ?>
<div class="eli_row eli_row-detail eli_content">
    <h4 class="eli_h4 eli_title">
        <a class="eli_a"><?php echo $event->eventName; ?></a>
    </h4>

    <span class="eli_address">
        <span class="eli_span eli_title">Presented by </span><?php echo $event->orgName. ', '. $event->venueName.', '.$event->venueCity; ?>
        
        <?php
            $start_d_arr = explode('-', $event->eventDateBegin);
            $start_date = mktime(0, 0, 0, $start_d_arr[0], $start_d_arr[1], $start_d_arr[2]);
            
            $end_d_arr = explode('-', $event->eventDateEnd);
            $end_date = mktime(0, 0, 0, $end_d_arr[0], $end_d_arr[1], $end_d_arr[2]);
        ?>
        
        <?php if ($start_date == $end_date) { ?>
            <p class="eli_p"><?php echo date('F j, Y', $start_date); ?></p>
        <?php } else { ?>
            <p class="eli_p"><?php echo date('F d', $start_date); ?> - <?php echo date('F d', $end_date); ?></p>
        <?php } ?>
    </span>

    <div class="eli_row-main">
        <a class="eli_a eli_img"><img src="<?php echo $event_img; ?>" class="eli_img" /></a>

        <div class="eli_information">
            <div class="eli_information-inner">
                <div class="eli_information-left">
                    <div class="eli_button-container-detail">

                        <?php if ($event->eventTicketUrl): ?>
                            <a target="_blank" href="<?php echo $event->eventTicketUrl ?>" class="eli_a eli_button eli_large eli_btn-buy-ticket"><span class="eli_span">Buy Tickets</span></a>
                        <?php endif; ?>
                            
                        <?php if ($event->discountUrl): ?>
                        <a target="_blank" href="<?php echo $event->discountUrl ?>" class="eli_a eli_button eli_large eli_green eli_btn-buy-ticket"><span class="eli_span">Check Discounts</span></a>
                        <?php endif; ?>
                        
                        
                        <a class="eli_a eli_m-r-5" href="http://www.facebook.com/sharer.php?u=<?php echo $link; ?>&t=<?php echo $event->eventName; ?>"
                        target="_blank" title="Share FB">
                            <img class="eli_img eli_fb_icon" alt="facebook icon" src="<?php echo plugins_url('artsopolis-calendar/img/facebook-icon.png'); ?>">
                        </a>

                        <a class="eli_a" href="https://twitter.com/share?url=<?php echo $link; ?>&text=<?php echo $event->eventName; ?>"
                        target="_blank" title="Share Twitter">
                            <img class="eli_img eli_tw_icon" alt="twitter icon" src="<?php echo plugins_url('artsopolis-calendar/img/twitter-icon.png'); ?>">
                        </a>
                       
                        
                        <?php if (!empty($event->eventUrl)): ?>
                        <a target="_blank" class="eli_a eli_event-website eli_font-s-16" href="<?php echo $event->eventUrl; ?>">Event Website</a>
                        <?php endif; ?>
                        
                    </div>
                </div>
            </div>
        </div> <!--#information -->
    </div> <!-- .row-main -->

    <div id="artsopolis-calendar-summary" class="eli_summary">
        <div class="eli_summary-short">
            <?php echo substr($event->eventDescription, 0, 200); ?>
            <?php if (strlen($event->eventDescription) > 200) { ?>
            <a href="javascript:void(0);" class="eli_a eli_expand-summary">[more+]</a>
            <?php } ?>
        </div>

        <div class="eli_summary-full eli_hidden">
            <?php echo $event->eventDescription; ?>
            <a href="javascript:void(0);" class="eli_a eli_less-summary">[less-]</a>
        </div>
    </div>
</div><!-- .row-detail -->
    
<div class="eli_row-detail eli_font-s-16">
	<h4 class="eli_h4 eli_heading-bar"><i class="eli_i eli_detail-icon eli_calendar-icon"></i>Dates & times</h4>
	<div class="eli_clear eli_content">
        <?php if ($event->event_dates_times): ?>
            <?php 
                $i = 0;
                $total_dates = count($event->event_dates_times);
                foreach ($event->event_dates_times as $date_time) { 

                $datetime = date('m/d/y', strtotime(substr($date_time->date, strpos($date_time->date, '-') + 1))) . ' '.$date_time->time;

                if ($i < 3) { ?>
                <span class="eli_span eli_full-price-content eli_full-w-float-l"><?php echo date('D, F d @ g:ia ', strtotime($datetime)); ?></span>
                <?php echo $i == 2 && $total_dates > 3 ? '<span class="eli_span eli_expand-more-dates"> + '.($total_dates - $i - 1).' more dates and times</span>' : ''; ?>
            <?php } else { 
                echo $i == 3 ? '<p class="eli_p eli_more-date eli_hidden">' : '';
            ?>
                <span class="eli_span eli_full-price-content eli_full-w-float-l"><?php echo date('D, F d @ g:ia ', strtotime($datetime)); ?></span>
            <?php 
                echo $i >= 3 && $i == $total_dates - 1 ? '<span class="eli_span eli_collapse-more-dates">- Less dates</span></p>' : '';
                }
                $i++;
            } ?>
        <?php endif; ?>
                
        <?php if ($event->eventStartTime): ?>        
        <div class="eli_p-t-10 eli_clearfix"><?php echo $event->eventStartTime; ?></div>
        <?php endif; ?>
    </div>
</div> <!-- End row-detail -->

<div class="eli_row-detail eli_font-s-16">
	<h4 class="eli_h4 eli_heading-bar"><i class="eli_i eli_admission-icon"></i>Admission</h4>
    <div class="eli_content">
        <p class="eli_p"><?php  echo $event->eventTicketInfo ? $event->eventTicketInfo : 'No infomation';  ?></p>
        <?php if ($event->eventPhone2): ?>
            <p class="eli_p"><?php echo $event->eventPhone2; ?></p>
        <?php endif; ?>
    </div>
</div> <!--.row-detail --> 

<div class="eli_row-detail">
	<h4 class="eli_h4 eli_heading-bar"><i class="eli_i eli_detail-icon eli_location-icon"></i>Location</h4>
	
    <div class="eli_content">
        <h6 class="eli_h6 eli_font-s-16">
            <a class="eli_a" target="_blank" href="<?= 'http://maps.google.com/maps?f=q&hl=en&ie=UTF8om=1&q=' . $gmap_address; ?>">
                <?php echo $event->venueName; ?>
                <img class="eli_img eli_arrow-map" alt="Arrow icon" src="<?php echo plugins_url('artsopolis-calendar/img/arrow-right-icon.png'); ?>">
            </a>
        </h6>

        <p class="eli_p eli_font-s-16">
            <?php 
                echo $event->venueAddress1;
                if ($event->venueAddress2) echo ', '.$event->venueAddress2; 
                if ($event->venueCity) echo ', '.$event->venueCity;
                if ($event->venueState) echo ', '.$event->venueState;
                if ($event->venueZip) echo ' '.$event->venueZip;
            ?>

        </p>
        <?php if ($event->eventPhone1): ?>
        <p class="eli_p eli_font-s-16">
            <?php echo $event->eventPhone1; ?>
        </p>
        <?php endif; ?>
        <div id="artsopolis_calendar_map_canvas" class="eli_m-t-15"></div>
        <p class="eli_p eli_full-map">
            <a class="eli_a" target="_blank" href="<?= 'http://maps.google.com/maps?f=q&hl=en&ie=UTF8om=1&q=' . $gmap_address; ?>">
                Full map and directions 
                <img class="eli_img eli_arrow-map" alt="Arrow icon" src="<?php echo plugins_url('artsopolis-calendar/img/arrow-right-icon.png'); ?>">
            </a>
        </p>
    </div>
</div> <!--.row-detail -->

<script>

    var __elisoft = typeof __elisoft  === 'undefined'  ? {} : __elisoft;
    __elisoft.art_calendar = {};
    <?php if (1==2 && $event->venueLatitude &&  $event->venueLongitude): ?>
        __elisoft.art_calendar.latitude  = '<?php echo $event->venueLatitude; ?>';
        __elisoft.art_calendar.longitude = '<?php echo $event->venueLongitude; ?>';
    <?php else: ?>
        __elisoft.art_calendar.gmap_address = '<?php echo $gmap_address; ?>';
    <?php endif ?>

</script>
<?php } else { ?>
    This event not exist or deleted
<?php } ?>