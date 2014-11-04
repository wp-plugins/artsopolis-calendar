<?php
// $is_show_error: show error or not
// $is_show_error == false mean: at the first time don't save any api
?>
<?php require_once '_admin_menu.php'; ?>
<div class="wrap artsopolis-calendar">
    <?php settings_errors(); ?>
    <div id="icon-edit-pages" class="icon32"></div>
    <h2>Update Artsopolis Calendar Settings</h2>

    <div id="artsopolis-calendar-notice" class="error hidden below-h2">

    </div>

    <div id="artsopolis-calendar-body" class="metabox-holder columns-2">
        <div id="artsopolis-calendar-body-content">
            <form method="post" action="options.php" id="artsopolis-calendar-settings-form">
            <?php settings_fields('artsopolis-calendar-group'); 
            do_settings_sections( 'artsopolis-calendar-group' ); ?>
            <!--titlediv-->
            <div id="titlediv">
                <div id="titlewrap">
                    <input type="text" name="artsopolis_calendar_options[title]" size="30" value="<?php echo isset( $artsopolis_calendar_options['title'] ) ? $artsopolis_calendar_options['title'] : '' ?>" id="title" autocomplete="off" placeholder="Enter the title">
                </div>
                <div class="inside">
                    <div id="edit-slug-box" class="hide-if-no-js">
                    </div>
                </div>
            </div>
            <!--//titlediv-->

            <div class="meta-box-sortables ui-sortable">
                <div id="artsopolis-calendar-info" class="postbox">
                    <h3 class="hndle"><span>Artsopolis Calendar API Information</span></h3>
                    <div class="inside">
                        <div id="artsopolis-calendar-info-detail" class="child">
                            <h4 class="hndle"><span>Calendar Affiliate XML Feed</span></h4>
                            <div class="inside">
                                <table class="form-table">
                                    <tbody>
                                        <tr valign="top">
                                            <th scope="row" class="feed-input">
                                                <label>XML Feed URL:</label>
                                            </th>
                                            <td class="xml-feed">
                                                <input class="w-95-p" name="artsopolis_calendar_options[feed_url]" type="text" value="<?php echo $artsopolis_calendar_options['feed_url'] ?>" class="regular-text code">
                                                <input name="artsopolis_calendar_options[feed_valid]" type="hidden" value="<?php echo $artsopolis_calendar_options['feed_valid'] ?>">
                                                <input name="artsopolis_calendar_options[has_changed]" type="hidden" value="<?php echo $artsopolis_calendar_options['has_changed'] ?>">
                                                <input name="feed_hidden" type="hidden" value="<?php echo $artsopolis_calendar_options['feed_url'] ?>">
                                                <span id="checking-xml-feed" class="hidden">Checking XML Feed ...</span>
                                                <span class="artsopolis-calendar-status-container">
                                                    <span class="artsopolis-calendar-error <?php echo $artsopolis_calendar_options['feed_valid'] == 0 ? '' : 'hidden'; ?>">
                                                        <span class="artsopolis-calendar-message">The XML Feed is invalid</span>
                                                    </span>

                                                    <span class="artsopolis-calendar-success <?php echo $artsopolis_calendar_options['feed_valid'] ? '' : 'hidden'; ?>">
                                                        <span class="artsopolis-calendar-message">The XML Feed is valid</span>
                                                    </span>
                                                </span>
                                            </td>
                                        </tr>
                                        
                                        <tr valign="top">
                                            <th scope="row" class="feed-input">
                                                <label>Category XML Feed URL:</label>
                                            </th>
                                            <td class="xml-category">
                                                <input class="w-95-p" name="artsopolis_calendar_options[category_xml_feed_url]" type="text" value="<?php echo $artsopolis_calendar_options['category_xml_feed_url'] ?>" class="regular-text code">
                                                <input name="artsopolis_calendar_options[category_valid]" type="hidden" value="<?php echo $artsopolis_calendar_options['category_valid'] ?>">
                                                <span id="checking-category-xml" class="hidden">Checking Category XML Feed URL ...</span>
                                                <span class="artsopolis-calendar-status-container">
                                                    <span class="artsopolis-calendar-error <?php echo $artsopolis_calendar_options['category_valid'] == 0 ? '' : 'hidden'; ?>">
                                                        <span class="artsopolis-calendar-message">Category XML Feed UR is invalid</span>
                                                    </span>

                                                    <span class="artsopolis-calendar-success <?php echo $artsopolis_calendar_options['category_valid'] ? '' : 'hidden'; ?>">
                                                        <span class="artsopolis-calendar-message">Category XML Feed UR is valid</span>
                                                    </span>
                                                </span>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div><!-- #artsopolis-calendar-info-->

                <div id="artsopolis-calendar-content" class="postbox">
                    <?php wp_editor($artsopolis_calendar_options['content'], 'content', array('textarea_name' => 'artsopolis_calendar_options[content]')); ?>
                </div> <!-- #artsopolis-calendar-->
                
                                
                <div id="artsopolis-calendar-widget-settings" class="postbox">
                    <h3 class="hndle"><span>Teaser Widget Settings</span></h3>
                    <div class="inside">

                        <div class="artsopolis-calendar-settings-display child">
                            <h4 class="hndle"><span>URL Settings</span></h4>
                            <div class="inside">
                                <table class="form-table">
                                    <tbody>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-color">Artsopolis Calendar's slug:</label>
                                            </th>
                                            <td>
                                                <input name="artsopolis_calendar_options[calendar_slug]" type="text" id="calendar-slug" value="<?php echo $artsopolis_calendar_options['calendar_slug'] ?>" class="regular-text code">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>    
                            </div>
                        </div>
                        
                        <div class="artsopolis-calendar-settings-display child">
                            <h4 class="hndle"><span>Logo Settings</span></h4>
                            <div class="inside">
                                <table class="form-table">
                                    <tbody>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-color">URL:</label>
                                            </th>
                                            <td>
                                                
                                                    <input readonly="true" placeholder="Click to upload a new graphic" name="artsopolis_calendar_options[teaser_widget_logo_url]" type="text" id="teaser-widget-logo-url" value="<?php echo $artsopolis_calendar_options['teaser_widget_logo_url'] ?>" class="regular-text code">
                                                    <input  class="arts-upload-button button" type="button" value="Upload Logo" />

                                                    <input opt-name="teaser_widget_logo_url" 
                                                           id="arts-delete-teaser-logo" 
                                                           image-url="<?php echo $artsopolis_calendar_options['teaser_widget_logo_url'] ?>" 
                                                           class="delete-btn <?php if (! $artsopolis_calendar_options['teaser_widget_logo_url']) echo 'hidden'; ?>" 
                                                           type="button" value="&nbsp;" />
                                                    <span class="logo-deleting  hidden">Deleting Logo</span>
                                                    <div>
                                                        <a id="ac-teaser-widget-logo-link" href="<?php echo $artsopolis_calendar_options['teaser_widget_logo_url'] ?>">
                                                        <img
                                                            <?php if (! $artsopolis_calendar_options['teaser_widget_logo_url'] ): ?>
                                                            style="display: none"
                                                            <?php endif; ?>
                                                            class="thumb" src="<?php echo $artsopolis_calendar_options['teaser_widget_logo_url'] ?>" />
                                                        </a>
                                                    </div>
                                            </td>
                                        </tr>
                                        
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-color">Position:</label>
                                            </th>
                                            <td>
                                                <?php $teaser_widget_logo_position = $artsopolis_calendar_options['teaser_widget_logo_position'] ?>
                                                <select class="arts-select" name="artsopolis_calendar_options[teaser_widget_logo_position]">
                                                    <option <?php echo $teaser_widget_logo_position == 'b_left' ? 'selected' : '' ?> value="b_left">Bottom - Left</option>
                                                    <option <?php echo $teaser_widget_logo_position == 'b_right' ? 'selected' : '' ?> value="b_right">Bottom - Right</option>
                                                    <option <?php echo $teaser_widget_logo_position == 'b_center' ? 'selected' : '' ?> value="b_center">Bottom - Center</option>
                                                </select>
                                            </td>
                                        </tr>
                                        
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-color">Link to:</label>
                                            </th>
                                            <td>
                                                <input name="artsopolis_calendar_options[teaser_widget_logo_link_to]" type="text" id="teaser-widget-logo-link-to" value="<?php echo $artsopolis_calendar_options['teaser_widget_logo_link_to'] ?>" class="regular-text code">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>    
                            </div>
                        </div>
                        
                    </div>
                </div>    
                
                <div id="artsopolis-calendar-settings" class="postbox">
                    <h3 class="hndle"><span>Plugin Display Settings</span></h3>
                    <div class="inside">

                        <!--artsopolis-calendar-settings-display -->
                        <div class="artsopolis-calendar-settings-display child">
                            <h4 class="hndle"><span>Display Settings</span></h4>
                            <div class="inside">

                                <table class="form-table">
                                    <tbody>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-color">Navigation background color:</label>
                                            </th>
                                            <td>
                                                <input name="artsopolis_calendar_options[settings_display_color]" type="text" id="settings-display-color" value="<?php echo $artsopolis_calendar_options['settings_display_color'] ?>" class="nav-bg-color regular-text code">
                                            </td>
                                        </tr>
                                        
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-order">Default display order:</label>
                                            </th>
                                            <td>
                                                <select name="artsopolis_calendar_options[settings_display_order]" id="settings-display-order">
                                                    <?php foreach ($arr_filters as $key => $label): ?>
                                                        <option value="<?php echo $key ?>" <?php if( $artsopolis_calendar_options['settings_display_order'] == $key ) echo 'selected' ?> > 
                                                            <?php echo $label ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-order">Details link to:</label>
                                            </th>
                                            <td>
                                                <label><input type="radio" name ="artsopolis_calendar_options[details_link_to]" value="1" <?php if(isset($artsopolis_calendar_options["details_link_to"]) && $artsopolis_calendar_options["details_link_to"] == '1') echo 'checked="true"' ?>  /> Plugin </label>
                                        		<label><input type="radio" name ="artsopolis_calendar_options[details_link_to]" value="0" <?php if(isset($artsopolis_calendar_options["details_link_to"]) && $artsopolis_calendar_options["details_link_to"] == '0') echo 'checked="true"' ?> /> Source </label>
                                            </td>
                                        </tr>
                                        
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-order">Display Search Bar:</label>
                                            </th>
                                            <td>
                                                <label><input type="radio" name ="artsopolis_calendar_options[display_search_bar]" value="1" 
                                                    <?php if((isset($artsopolis_calendar_options["display_search_bar"]) && 
                                                    $artsopolis_calendar_options["display_search_bar"] == '1')
                                                    || ! isset($artsopolis_calendar_options["display_search_bar"])) echo 'checked="true"' ?>  /> Yes &nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        		<label><input type="radio" name ="artsopolis_calendar_options[display_search_bar]" value="0" <?php if(isset($artsopolis_calendar_options["display_search_bar"]) && $artsopolis_calendar_options["display_search_bar"] == '0') echo 'checked="true"' ?> /> No </label>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div><!--//artsopolis-calendar-settings-display -->
                        
                        
                        <div class="artsopolis-calendar-settings-display child">
                            <h4 class="hndle"><span>Logo Settings</span></h4>
                            <div class="inside">
                                <table class="form-table">
                                    <tbody>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-color">URL:</label>
                                            </th>
                                            <td>
                                                <input readonly="true" placeholder="Click to upload a new graphic" name="artsopolis_calendar_options[plugin_logo_url]" type="text" id="plugin-logo-url" value="<?php echo $artsopolis_calendar_options['plugin_logo_url'] ?>" class="regular-text code">
                                                <input class="arts-upload-button button" type="button" value="Upload Logo" />
                                                  
                                                <input opt-name="plugin_logo_url" id="arts-delete-plugin-logo" 
                                                       image-url="<?php echo $artsopolis_calendar_options['plugin_logo_url'] ?>" 
                                                       class="delete-btn <?php if (! $artsopolis_calendar_options['plugin_logo_url']) echo 'hidden'; ?>" 
                                                       type="button" value="&nbsp;" />
                                                <span class="logo-deleting hidden">Deleting Logo</span>
                                                
                                                <div>
                                                    <a  id="ac-plugin-logo-link"  href="<?php echo $artsopolis_calendar_options['plugin_logo_url'] ?>"><img
                                                    <?php if (! $artsopolis_calendar_options['plugin_logo_url'] ): ?>
                                                    style="display: none"
                                                    <?php endif; ?>
                                                    class="thumb" src="<?php echo $artsopolis_calendar_options['plugin_logo_url'] ?>" />
                                                    </a>
                                                </div>    
                                            </td>
                                        </tr>
                                        
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-color">Position:</label>
                                            </th>
                                            <td>
                                                <?php $plugin_logo_position = $artsopolis_calendar_options['plugin_logo_position'] ?>
                                                <select  class="arts-select" name="artsopolis_calendar_options[plugin_logo_position]">
                                                    <option <?php echo $plugin_logo_position == 't_left' ? 'selected' : '' ?> value="t_left">Top - Left</option>
                                                    <option <?php echo $plugin_logo_position == 't_right' ? 'selected' : '' ?> value="t_right">Top - Right</option>
                                                    <option <?php echo $plugin_logo_position == 't_center' ? 'selected' : '' ?> value="t_center">Top - Center</option>
                                                    <option <?php echo $plugin_logo_position == 'b_left' ? 'selected' : '' ?> value="b_left">Bottom - Left</option>
                                                    <option <?php echo $plugin_logo_position == 'b_right' ? 'selected' : '' ?> value="b_right">Bottom - Right</option>
                                                    <option <?php echo $plugin_logo_position == 'b_center' ? 'selected' : '' ?> value="b_center">Bottom - Center</option>
                                                    <option <?php echo $plugin_logo_position == 'tb_left' ? 'selected' : '' ?> value="tb_left">Top - Bottom - Left</option>
                                                    <option <?php echo $plugin_logo_position == 'tb_right' ? 'selected' : '' ?> value="tb_right">Top - Bottom - Right</option>
                                                    <option <?php echo $plugin_logo_position == 'tb_center' ? 'selected' : '' ?> value="tb_center">Top - Bottom - Center</option>
                                                </select>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>    
                            </div>
                        </div>
                        
                        <!-- artsopolis-calendar-settings-category -->
                        <div class="artsopolis-calendar-settings-category child" id="artsopolis-calendar-list-categories">
                            <h4 class="hndle"><span>Category Selection</span></h4>
                            <div class="inside clearfix" id="wrapper-category">
                                <?php include dirname(__FILE__) . '/_category-template.php'; ?>
                            </div>
                            <div style="clear: both"></div>
                        </div><!-- #artsopolis-calendar-settings-category -->

                        <!-- artsopolis-calendar-settings-category -->
                        <div class="artsopolis-calendar-settings-filter-display child">
                            <h4 class="hndle"><span>Filter Display Settings</span></h4>
                            <div class="inside">

                                <?php foreach ($arr_filter_settings as $index => $filter_name):
                                    $slug_filter_name = strtolower($filter_name);
                                    ?>
                                    <div class="row">
                                        <div class="desc"><?php echo $filter_name ?> filter</div>
                                        <label><input type="radio" name ="artsopolis_calendar_options[filter_<?php echo $slug_filter_name ?>]" value="1" <?php if(isset($artsopolis_calendar_options["filter_$slug_filter_name"]) && $artsopolis_calendar_options["filter_$slug_filter_name"] == '1') echo 'checked="true"' ?>  /> On </label>
                                        <label><input type="radio" name ="artsopolis_calendar_options[filter_<?php echo $slug_filter_name; ?>]" value="0" <?php if(isset($artsopolis_calendar_options["filter_$slug_filter_name"]) && $artsopolis_calendar_options["filter_$slug_filter_name"] == '0') echo 'checked="true"' ?> /> Off </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div><!-- #artsopolis-calendar-settings-category -->

                        <?php submit_button(); ?>

                    </div><!-- inside 1 -->

                </div><!-- //#artsopolis-calendar-settings -->
            </div><!-- //meta-box-sortables ui-sortable -->

            </form><!-- #end form -->
        </div><!-- #artsopolis-calendar-body-content -->
    </div> <!-- #artsopolis-calendar-body -->

</div>  <!-- #wrap-->

<script type="text/javascript">
    var artsopolis_calendar_obj = artsopolis_calendar_obj || {};
    artsopolis_calendar_obj.admin_url = '<?php echo $admin_url ?>';
</script>