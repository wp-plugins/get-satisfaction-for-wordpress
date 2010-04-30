<?php
/**
 * Database Helper Functions
 * 
 * TODO : Add caching support
 * 
 * @author dcheung
 */
if (!class_exists('GetSatisfactionDatabase')) {
    class GetSatisfactionDatabase {	
        /**
         * Check for if topic_id is a duplicate. 
         * The check is made against the post meta key value
         * and the topic id url string
         * 
         * @param $topic_id url string of topic
         */
        static function is_topic_duplicate($topic_id) {
            global $wpdb;
            
            $key = 'gs.topic.id';
            $value = $topic_id;
            $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key = '$key' AND meta_value = '$value';"));
            if ($count > 0) {
                return true;
            } 
            return false;
        }
    
        /**
         * Check for if reply_id is a duplicate. 
         * The check is made against the post meta key value
         * and the reply id url string
         * 
         * @param $reply_id
         */
        static function is_reply_duplicate($reply_id) {
            global $wpdb;
            
            $key = 'gs.reply.id';
            $value = $reply_id;
            $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->commentmeta WHERE meta_key = '$key' AND meta_value = '$value';"));
            if ($count > 0) {
                return true;
            } 
            return false;
        }
        
        /**
         * Get the post_id by topic_id url
         *
         * @param $topic_id
         */
        static function get_post_id($topic_id) {
            global $wpdb;
            
            $key = 'gs.topic.id';
            $value = $topic_id;
            $post_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '$key' AND meta_value = '$value';"));
    
            return $post_id;
        }	
    }
}
?>