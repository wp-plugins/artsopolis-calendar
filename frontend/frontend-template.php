<?php

/**
 * This file is part of the Artsopolis Calendar Plugin.
 *
 * (c) vulh@elinext.com <http://elinext.com>
 *
 * This source file is subject to the elinext.com license that is bundled
 * with this source code in the file LICENSE.
 */
if (!session_id()) {
    session_start();
}

$logo_position = explode('_', $ac_options['plugin_logo_position']);

?>
<div class="eli_wrap artsopolis-calendar-frontend">
    <div class="eli_wrap-inner">
        <?php 
        if(! empty($ac_options['title'])): ?>
        <div class="eli_produce-wrap">
            <div class="eli_produce-wrap-inner">
                <h2 class="eli_h2"><?php echo $ac_options['title'] ?></h2>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($logo_position[0] == 't' || $logo_position[0] == 'tb'): ?>
        <div style="text-align: <?php echo isset($logo_position[1]) && $logo_position[1] ? $logo_position[1] : 'right' ?>">
            <a href="<?php echo $ac_options['teaser_widget_logo_link_to'];  ?>" target="_blank">
            <?php if (  $ac_options['plugin_logo_url'] ): ?>
                <img  src="<?php echo $ac_options['plugin_logo_url'];  ?>" />
            <?php endif; ?>
            </a>
        </div>
        <?php endif; ?>
        <div class="eli_content-can-filter">
            <div class="eli_content-can-filter-inner">
                <div class="eli_content">
                    <div class="eli_content-inner ">
                        <?php if ( ( ! isset( $ac_options['display_search_bar'] ) ) || ( isset( $ac_options['display_search_bar'] ) && $ac_options['display_search_bar'] == '1' ) ) : ?>
                        <div id="artsopolis-calendar-filter" class="eli_filter eli_clearfix" style="background-color: <?php echo isset($ac_options['settings_display_color']) ? $ac_options['settings_display_color']: '#e7e7e7'; ?> !important">
                            <div class="eli_filter-inner">
                                <div class="eli_filter-item eli_inner-full">
                                    <input name="keyword" class="eli_input eli_keyword eli_m-r-10" id="keyword" placeholder="Keyword" />

                                    <?php 
                                    if(! empty($ac_category)): ?>
                                        <div class="eli_category-filter eli_filter-item">
                                            <select class="eli_select eli_m-r-10 " name="category" placeholder="Category" id="filter-by-category">
                                                <option value=""> Category </option>

                                                <?php 
                                                $sub_prefix = '';
                                                $category_list = array ();
                                                foreach($ac_category as $key => $val): $category_list[] = $key;?>
                                                    <?php if (isset($val['name']) && $val['name']): $sub_prefix = '-- ';?>
                                                    <option value="<?php echo $key ?>[+]<?php echo $val['name']; ?>"> <?php echo $val['name']; ?></option>
                                                    <?php endif; ?>

                                                    <?php 
                                                        // Display the list subs cat
                                                        if (! isset($val['subs']) && ! isset( $val['subs'] )) continue;
                                                        foreach ($val['subs'] as $key => $sub_name) :$category_list[] = $key; 
                                                    ?>
                                                        <option value="<?php echo $key ?>[+]<?php echo $sub_name; ?>"><?php echo $sub_prefix. $sub_name; ?></option>
                                                    <?php endforeach; ?>	
                                                <?php endforeach; ?>
                                            </select>

                                            <input value="<?php echo implode(',', $category_list); ?>" type="hidden" name="category-list" class="eli_input" id="category-list" />
                                            <input type="hidden" name="tags-category" class="eli_input" id="tags-category" category-name="" />
                                        </div>
                                    <?php endif; ?>

                                    <?php if($ac_filter_location == 1): ?>
                                        <div class="eli_location-filter eli_filter-item">
                                            <select name="location" placeholder="Location" id="filter-by-location" class="eli_select">
                                                <option value=""> Location </option>
                                                <?php foreach ($locations as $location) : ?>
                                                    <option value="<?php echo $location; ?>"><?php echo $location; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($ac_filter_date == 1): ?>
                                    <div class="eli_date-filter eli_filter-item">
                                        <div class="eli_date-filter-from-to">
                                            <div class="eli_date-filter-from-wrapper">
                                                <input type="text" name="from_date" placeholder="Start date" class="eli_input eli_from-date eli_m-r-10 " id="artsopolis-calendar-filter-from-date" />
                                            </div>
                                            <div class="eli_date-filter-to-wrapper">
                                                <input type="text" name="to_date" placeholder="End date" class="eli_input eli_end-date" id="artsopolis-calendar-filter-to-date"/>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>

                                    <div class="eli_filter-item">
                                         <div class="eli_date-util">
                                            <?php if($ac_filter_date == 1): ?> 
                                            <a href="javascript:void(0);" id="artsopolis-calendar-choice-today" 
                                               data-date-from=""
                                               data-date-to=""
                                               class="eli_a eli_button eli_button-gray eli_ac-btn-filter">Today</a>
                                            <a href="javascript:void(0);" id="artsopolis-calendar-choice-tomorrow"
                                               data-date-from=""
                                               data-date-to=""
                                               class="eli_a eli_button eli_button-gray eli_ac-btn-filter">Tomorrow</a>
                                            <a href="javascript:void(0);" id="artsopolis-calendar-choice-weekend"
                                               data-date-from=""
                                               data-date-to=""
                                               class="eli_a eli_button eli_button-gray eli_ac-btn-filter">This Weekend</a>
                                            <?php endif; ?>   
                                            <a href="javascript:void(0);" id="reset"
                                               class="eli_a eli_button eli_button-gray">Reset</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div><!-- #end filter -->
                        <?php endif; ?>
                        
                        <div class="eli_powered-by"><?php echo $ac_options['content']; ?></div>
                        
                        <?php if ( isset( $_GET['event_id'] ) && $_GET['event_id'] ): ?>
                            <div id="artsopolis-calendar-detail-event">
                                <?php echo $html_events; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div id="artsopolis-calendar-tabs-events" class="tabs <?php if ( isset( $_GET['event_id'] ) && $_GET['event_id'] ) { echo ' eli_hidden '; $html_events = ''; } ?>">
                            <ul class="tab-links">
                                <li class="active"><a href="#current-upcoming">Current & Upcoming</a></li>
                                <li id="ongoing-tab"><a href="#ongoing">Ongoing</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="current-upcoming" class="tab active">
                                    <div <?php if (! isset($_GET['event_id'])) echo 'class="eli_list"'; ?> id="artsopolis-calendar-list-feed">
                                        <?php echo $html_events; ?>
                                    </div>
                                    
                                    <!-- Only required it in the list events -->                
                                    <div class="eli_pagination" id="artsopolis-calendar-pagination"></div>
                                    <script type="text/javascript">
                                        var artsopolis_calendar_paging = {
                                            total_event: <?php echo isset($total_event) && $total_event ? $total_event : 0; ?>,
                                            page_size: <?php echo isset($page_size) && $page_size ? $page_size : 0; ?>
                                        };
                                    </script>
                                </div>

                                <div id="ongoing" class="tab">
                                    <div id="ongoing-container" <?php if (! isset($_GET['event_id'])) echo 'class="eli_list"'; ?> >
                                        <?php echo $html_events; ?>
                                    </div>
                                    <!-- Only required it in the list events -->                
                                    <div class="eli_pagination" id="artsopolis-calendar-pagination-second-tab"></div>
                                    <script type="text/javascript">
                                        var artsopolis_calendar_paging_second_tab = {
                                            total_event: 0,
                                            page_size: artsopolis_calendar_paging.page_size
                                        };
                                    </script>
                                </div>

                            </div>
                        </div>
                       
                    </div>
                </div>
            </div><!-- #content-can-filter-inner-->
        </div><!-- #content-can-filter-->
    </div>
</div><!-- #wrap -->
<?php if ($logo_position[0] == 'b' || $logo_position[0] == 'tb'): ?>
<div style="text-align: <?php echo isset($logo_position[1]) && $logo_position[1] ? $logo_position[1] : 'right' ?>">
    <a href="<?php echo $ac_options['teaser_widget_logo_link_to'];  ?>" target="_blank">
    <?php if (  $ac_options['plugin_logo_url']  ): ?>    
        <img src="<?php echo $ac_options['plugin_logo_url'];  ?>" />
    <?php endif; ?>
    </a>
</div>
<?php endif; ?>