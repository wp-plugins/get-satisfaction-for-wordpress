<?php

include_once('getsatisfaction-config.php');
include_once('classes/Satisfaction.php');
include_once('getsatisfaction-database.php');

/**
 * Template Tags
 * 
 * Can be used within your blog templates to display information 
 * dynamically or otherwise customize your blog for GetSatisfaction Topic
 * data.
 */

/**
 * Set the photo url for a reply
 * @param string url $comment_id
 */
function get_gs_user_photo_url($comment_id) {
    return get_comment_meta($comment_id, 'gs.reply.photo_url', true);    
}

/**
 * Get the author for a topic
 * @param int $post_id
 */
function get_gs_author($post_id) {
    $user = array();
    
    $user['name'] = get_post_meta($post_id, 'gs.topic.user.name', true);
    $user['profile_url'] = get_post_meta($post_id, 'gs.topic.user.url', true);
    $user['photo_url'] = get_post_meta($post_id, 'gs.topic.user.photo', true);

    return $user;    
}

/**
 * Get all the metadata for a topic which includes the following :
 * 
 * Topic Style
 * Company Url
 * Replies Url
 * Reply Url
 * 
 * @param $post_id
 */
function get_gs_topic_metadata($post_id) {
    $topic_metadata = array();
    
    $topic_metadata['topic_id'] = get_post_meta($post_id, 'gs.topic.sfn_id', true);
    $topic_metadata['topic_style'] = get_post_meta($post_id, 'gs.topic.style', true);
    $topic_metadata['company_url'] = get_post_meta($post_id, 'gs.topic.company_url', true);
    $topic_metadata['replies_url'] = get_post_meta($post_id, 'gs.topic.replies_url', true);
    $topic_metadata['reply_url'] = get_post_meta($post_id, 'gs.topic.reply_url', true);
    
    return $topic_metadata;    
}

/**
 * Get the replies url for this Topic
 * @param int $post_id
 */
function get_gs_topic_replies($post_id) {
    $topic_metadata = array();
    $topic_metadata['replies_url'] = get_post_meta($post_id, 'gs.topic.replies_url', true);    
}

/**
 * Get the company information associated to Topic
 * 
 * @param int $post_id
 */
function get_company($post_id) {
    $company = array();

    $company['name'] = get_post_meta($post_id, 'gs.topic.company.name', true);
    $company['logo_url'] = get_post_meta($post_id, 'gs.topic.company.logo_url', true);
    $company['note'] = get_post_meta($post_id, 'gs.topic.company.note', true);
    $company['organization'] = get_post_meta($post_id, 'gs.topic.company.organization', true);
    $company['url'] = get_post_meta($post_id, 'gs.topic.company.url', true);
    
    return $company;    
}

/**
 * Topic comment template view
 */
function topic_comment($comment, $args, $depth) {
    include('views/comment-template.php');
}

// Add Company Information To Content
add_filter('the_content', 'add_company_info_content' );

/**
 * Add Company Info to Content
 */
function add_company_info_content($content) {
    global $post;
    $content = $post->post_content;
    if ($post->post_type == 'topic') {
        include('views/content-template.php');
    } else {
        return $content;;
    }
}
?>