<?php
/*
Plugin Name: Google Alerts Snippets Autopopulate Cron
Plugin URI: https://github.com/
Description: Populates the WP database with result snippets from a Google Alert feed
Version: 1.0
Author: Freeman Fan
Author URI: http://www.bitcoinlode.com
*/

/* add interval setting */
// add_filter( 'cron_schedules', 'bl_add_cron_intervals' );
// function bl_add_cron_intervals( $schedules ) {
//    $schedules['5seconds'] = array(
//       'interval' => 5,
//       'display' => __('Every 5 Seconds')
//    );
//    return $schedules;
// }

/* schedule cron task */
add_action('bl_cron_hook', 'addPostsFromFeed');
if (!wp_next_scheduled('bl_cron_hook')) {
   wp_schedule_event(time(), 'hourly', 'bl_cron_hook');
}

/* unschedule cron task */
// $timestamp = wp_next_scheduled( 'bl_cron_hook' );
// wp_unschedule_event($timestamp, 'bl_cron_hook' );

//var_dump(wp_next_scheduled('bl_cron_hook'));




function addPostsFromFeed() {
        echo "Adding posts from feed";
    // get list of existing post titles
    global $wpdb;
    $posts = $wpdb->get_results( 
        "
        SELECT ID, post_title 
        FROM $wpdb->posts
        "
    );

    // get new articles from feed
    $url = 'http://www.google.com/alerts/feeds/08800533642196881169/12722183719997630847';
    $xmlStr = @file_get_contents( $url ); // @ will surpress errors
    $feed = new SimpleXMLElement($xmlStr);

    foreach ($feed->entry as $entry) {
        $postExists = false;
        foreach($entry->title as $title) {
            if ((string) $title['type'] == html) { // title found
                foreach ($posts as $post) {
                    if ($post->post_title == $title) {
                        $postExists = true;
                        break;
                    }
                }
            }
        }
        if ($postExists) continue;

        //echo $title;
        //echo ("<br /><br />");

        $url = parse_url($entry->link['href']);
        $queryStr = $url['query'];
        if (isset($queryStr)) {
            $pairs = explode('&', $queryStr);
            foreach($pairs as $pair) {
                $part = explode('=', $pair);
                if ($part[0] == 'q') {
                    $articleUrl = urldecode(($part[1]));
                }
            }
        }

        $content = preg_replace('/<a(.*?)<\/a>/i', "", $entry->content);

        $blog = $entry->author->name . "<br /><br />"
                . $content . "<br />"
                . "<a href='" . $articleUrl . "'>Read Full Text</a><br />";

        //echo $blog;
        //echo ("<br /><br /><br /><br /><br />");

        // insert new post in db
        $new_post = array(
            'post_title' => (string)$title,
            'post_content' => (string)$blog,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'post',
            'post_category' => array(0)
        );
        wp_insert_post($new_post);
    }
}
