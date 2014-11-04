<?php

/**
 * This file is part of the Artsopolis Calendar Plugin.
 *
 * (c) vulh@elinext.com <http://elinext.com>
 *
 * This source file is subject to the elinext.com license that is bundled
 * with this source code in the file LICENSE.
 */
?>

<?php 

// Start to get the current url
if (!session_id()) {
    session_start();
}

$numShowDate = 3;
if(!empty($events)):
foreach ($events as $event): 
    $event = self::get_cdata_xml($event);
    $event_img = self::get_event_url($event->eventImage);
	
	if (! $ac_options['details_link_to']) {
		$link = $event->link;
		$target = 'target="_blank"';
	} else {
		// Custom url follow the permalink structure
		$url_ext_sign = ! get_option('permalink_structure') ? '&' : '?';
		$parent_link = get_site_url().'/'. $ac_options['calendar_slug']; 
		$link = $parent_link. $url_ext_sign .'event_id='. $event->eventID;
		$target = '';
	}
   
?>

<div class="eli_row">
<h4 class="eli_h4 eli_title">
    <a class="eli_a" <?php echo $target; ?> href="<?php echo $link; ?>"><?php echo $event->eventName; ?></a>
</h4>
    
<span class="eli_span eli_address">
    <span class="eli_span eli_title">Presented by </span><?php echo $event->orgName. ', '. $event->venueName.', '.$event->venueCity; ?>
</span>
<div class="eli_row-main">
    <a <?php echo $target; ?> href="<?php echo $link; ?>" class="eli_a eli_img"><img src="<?php echo $event_img; ?>" class="eli_img" /></a>

    <div class="eli_information">
        <div class="eli_information-inner">
            <div class="eli_information-left">
                <?php if ($event->event_dates_times): ?>
                <div class="eli_offer-date clear">
                    <span class="eli_span eli_offer-date-title">Upcoming Dates:</span>
                    <?php 
                        $i = 0;
                        $total_dates = count($event->event_dates_times);
                        foreach ($event->event_dates_times as $date_time) { 
                            
                           
                        $datetime = date('m/d/y', strtotime(substr($date_time->date, strpos($date_time->date, '-') + 1))) . ' '.$date_time->time;
                       
                        if ($i < 3) { ?>
                        <span class="eli_span eli_full-price-content eli_full-w-float-l"><?php echo date('D, F d @ g:ia ', strtotime($datetime)); ?></span>
                        <?php echo $i == 2 && $total_dates > 3 ? '<span class="eli_expand-more-dates"> + '.($total_dates - $i - 1).' more dates and times</span>' : ''; ?>
                    <?php } else { 
                        echo $i == 3 ? '<p class="eli_p eli_more-date eli_hidden">' : '';
                    ?>
                        <span class="eli_span eli_full-price-content eli_full-w-float-l"><?php echo date('D, F d @ g:ia ', strtotime($datetime)); ?></span>
                    <?php 
                        echo $i >= 3 && $i == $total_dates - 1 ? '<span class="eli_span eli_collapse-more-dates">- less dates and times</span></p>' : '';
                        }
                        $i++;
                    } ?>
                    
                </div>
                <?php endif; ?>
            </div>
            
            <div class="eli_information-right">
                <?php if ($event->eventTicketUrl): ?>
                <div class="eli_button-container">
                    <a  target="_blank" href="<?php echo $event->eventTicketUrl ?>" class="eli_a eli_button eli_large"><span class="eli_span">Buy Tickets</span></a>
                </div>
                <?php endif; ?>
                
                <?php if ($event->discountUrl): ?>
                <div class="eli_button-container">
                    <a target="_blank" href="<?php echo $event->discountUrl ?>" class="eli_a eli_button eli_large eli_green eli_m-t-5"><span class="eli_span">Check Discounts</span></a>
                </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div> <!--#information -->

</div>

<div class="artsopolis-calendar-summary eli_summary">
    <div class="eli_summary-short">
        <?php 
        
        $desc = strip_tags( str_replace('<br />', '<br/>', html_entity_decode($event->eventDescription)) ) ;
        $short = explode( ' ',  $desc );
        echo implode(' ', array_slice( $short, 0, 30 ));
        ?>
        
        <?php if ( count( $short ) > 30 ) { ?>
        <a href="javascript:void(0);" class="eli_a eli_expand-summary">[more+]</a>
        <?php } ?>
       
    </div>
    
    <div class="eli_summary-full eli_hidden">
        <?php echo $event->eventDescription; ?>
        <a href="javascript:void(0);" class="eli_a eli_less-summary">[less-]</a>
    </div>
</div>
    
<?php if ($event->tags) :
    $tags = explode(',', $event->tags); 
    $categories = explode(',', $event->categories); 
    
    
    $cat_comb = array();
    $i = 0;
    foreach ($categories as $cat) {
        if (isset($selected_category) && in_array($categories[$i], $selected_category)) {
            $cat_comb[] = $tags[$i]. '[+]'. $cat;
        }
        $i++;
    }
    $cat_comb_sort = self::sort_tags($cat_comb);
    
    $flag = 0;
?>
    <h6 class="eli_h6 eli_tags">Tags:<?php foreach ($cat_comb_sort as $tag): ?><?php $tag = explode('[+]', $tag); ?><?php echo $flag > 0 ? ',' : ''; ?>&nbsp;<a class="eli_a" category-name="<?php echo $tag[0]; ?>" category='<?php echo $tag[1]; ?>'><?php echo $tag[0]; ?></a><?php $flag++; endforeach; ?>
    </h6> 
<?php endif; ?>
<div style="clear:both"></div>

</div><!-- #end row -->
<?php endforeach; ?>

<?php if(isset($arr_filter['repagination']) && $arr_filter['repagination'] === 'yes'): ?>
    <script type="text/javascript">
        var artsopolis_calendar_paging = artsopolis_calendar_paging || {};
        artsopolis_calendar_paging = {
            total_event: <?php echo $total_event ?>,
            page_size: <?php echo $page_size ?>
        };
    </script>
    <?php endif; ?>


<?php else: ?>
    <div class="note">
        <?php if (isset($ac_options['category']) && $ac_options['category']) { ?>
        Your search did not return any matching results. Please <a class="eli_a artsopolis-calendar-search-again">search again</a>.
        <?php } else { ?>
        Please select at least a category to display events <a class="eli_a" href="/wp-admin/plugins.php?page=admin-artsopolis-calendar">Click here</a>
        <?php } ?>
        <script type="text/javascript">
            var artsopolis_calendar_paging =  artsopolis_calendar_paging || {};
            artsopolis_calendar_paging.total_event = 0;
        </script>
    </div>
<?php endif; ?>
