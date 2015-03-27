<?php 

function ac_sort_by_start_date($a, $b) {
    if (! $a->eventDateBegin || ! $b->eventDateBegin) {
        return true;
    }
    
    $bg1 = explode('-', (string) $a->eventDateBegin);
    $bg2 = explode('-', (string) $b->eventDateBegin);
    
    $s1 = mktime(0, 0, 0, $bg1[0], $bg1[1], $bg1[2]);
    $s2 = mktime(0, 0, 0, $bg2[0], $bg2[1], $bg2[2]);
    
    return $s1 > $s2;
}

function ac_sort_by_start_upcomming_time( $a, $b ) {
    if ( ! $a->eventDatesTimes->datetime || ! $b->eventDatesTimes->datetime ) {
        return true;
    }
    
    $s1 = $a->eventDatesTimes->datetime[0]->timestamp;
    $s2 = $b->eventDatesTimes->datetime[0]->timestamp;
    
    if ( intval( $s1 ) == intval( $s2 ) ) {
        return ac_sort_by_start_date($a, $b);
    }
    
    return intval( $s1 ) > intval( $s2 );
}

function ac_sort_by_end_date($a, $b) {
    if (! $a->eventDateEnd || ! $b->eventDateEnd) {
        return true;
    }
    
    $bg1 = explode('-', (string) $a->eventDateEnd);
    $bg2 = explode('-', (string) $b->eventDateEnd);
    
    $s1 = mktime(0, 0, 0, $bg1[0], $bg1[1], $bg1[2]);
    $s2 = mktime(0, 0, 0, $bg2[0], $bg2[1], $bg2[2]);
    
    return $s1 > $s2;
}

function ac_sort_by_alpha($a, $b) {
    $s1 = str_replace(' ', '', (string) $a->eventName);
    $s2 = str_replace(' ', '', (string) $b->eventName);
    return strcasecmp($s1 , $s2);
}

function ac_admin_parent_sort_by_alpha($a, $b) {
    return strcasecmp($a['name'] , $b['name']);
}

function ac_admin_sub_sort_by_alpha($a, $b) {   
    return strcasecmp($a , $b);
}

if ( ! function_exists( 'ac_get_current_domain' ) ) {
    function ac_get_current_domain() {
        
        if ( is_multisite() ) {
            $site = get_blog_details(get_current_blog_id());
            return $site->domain;
        }
        
        return '';
    }
    
}