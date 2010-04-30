<?php
    global $gs_url;
    $gs_url = "http://getsatisfaction.com";

    global $api_root;
    $api_root = "http://api.getsatisfaction.com/";

    global $sfn_root;
    $sfn_root = "http://getsatisfaction.com/";
    
    global $oauth_request_timeout;
    $oauth_request_timeout = 10; # seconds (accepts floating point)
    
    global $http_cache_timeout;
    $http_cache_timeout = 3600; # seconds (must be whole number)    
    
    global $submit_suggestions;
    $submit_suggestions = 3;

    global $helpstart_topic_count;
    $helpstart_topic_count = 5; 
    
    global $discuss_page_size;
    $discuss_page_size = 10;
    
    global $discuss_page_size;
    $related_topics_count = 4;
    
    global $max_top_topic_tags;
    $max_top_topic_tags = 5;
    
    global $topic_page_size;
    $topic_page_size = 5; # TBD: regularize these names
?>