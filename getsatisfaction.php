<?php
/*
Plugin Name: Get Satisfaction
Plugin URI: http://www.getsatisfaction.com
Author: Get Satisfaction
Author URI: http://www.getsatisfaction.com
Version: 0.1

Description: Get Satisfaction plugin to allow the creation, management, viewing
of Topics with in Wordpress

Installation: 			 

1. Install Pear Library
*/

/* Constants */
define ('GET_SATISFACTION_DB_VERSION', '0.1');

if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );


/* Includes */
// require_once(ABSPATH . WPINC . '/registration.php');
include_once('getsatisfaction-service-api.php');
include_once('getsatisfaction-database.php');
include_once('getsatisfaction-admin.php');
include_once('getsatisfaction-templatetags.php');

/* Plugin Definition */
if (!class_exists('GetSatisfaction')) {
    class GetSatisfaction {
    	private $database;
    	private $pluginurl;
    	private $pluginpath;
        private $localizationDomain = "GetSatisfaction";
    	
    	function __construct() {
    		$this->database = new GetSatisfactionDatabase;

    		/* Constants */
            $this->pluginurl = PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__)).'/';
            $this->pluginpath = PLUGIN_PATH . '/' . dirname(plugin_basename(__FILE__)).'/';    		
    		
    		/* Language Setup */
    		$locale = get_locale();
            $mo = dirname(__FILE__) . "/languages/" . $this->localizationDomain . "-".$locale.".mo";
            load_textdomain($this->localizationDomain, $mo);
    		
    		/* Hook Registration */
    		if (is_admin ()) {
    			register_activation_hook( __FILE__, array (&$this, 'get_satisfaction_install'));
    			register_deactivation_hook( __FILE__, array (&$this, 'get_satisfaction_uninstall'));			
     		}
     		
            /* Actions */
    		add_action('init', array(&$this, 'register_type'), false, 2);		
            add_action("template_redirect", array(&$this, 'template_redirect'), false, 2);
            
            add_action("save_post", array(&$this, 'save_topic_editor_postdata'), false, 2);
            add_action("admin_menu", array(&$this, 'add_topic_editor'), false, 2);
            
            /* Widget Registration Actions */
            // add_action('plugins_loaded', array(&$this, 'register_widgets'));
            
            /* Add CSS and Javascript */
            add_action("wp_head", array(&$this, 'add_css'), false, 2);
            // add_action('wp_print_scripts', array(&$this, 'add_js'));

            /* Filters */
            add_filter('pre_get_posts', array(&$this, 'add_topic_get_posts'));

            // Add All Post Type
            add_filter('request',array(&$this, 'add_all_post_type'));
            
            // add_filter('the_content', array(&$this, 'filter_content'), 0);            
    	}
    	
    	/**
    	 * add_css() - add css
    	 * 
    	 * This function will include the plugin css
    	 */
        function add_css() {
            echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') .'/wp-content/plugins/get-satisfaction-for-wordpress/css/structure.css" />' . "\n";
	    }    	
    	
    	/**
    	 * add_css() - add css
    	 * 
    	 * This function will include the plugin css
    	 */
        function add_js() {            
            //echo '<script src="' . get_bloginfo('wpurl') .'/wp-content/plugins/get-satisfaction-for-wordpress/js/general.js" />' . "\n";
	    }    	
	    
	    /**
         * Add Topic to Get Post for Loop
         * @param $query
         */
        function add_topic_get_posts($query) {
            if (is_home())
                $query->set('post_type', array('post', 'topic', 'attachment'));
        
            return $query;
        }    	    	
	    
    	/**
    	 * register_type() - register get satisfaction topic type
    	 *
    	 * This function installs the plugin's database tables, setup option for
    	 * the plugin
    	 *
    	 * @param void      none
    	 * @return void     none  	 
    	 */
         function register_type() {
            $icon_url = get_bloginfo('wpurl') .'/wp-content/plugins/get-satisfaction-for-wordpress/images/gs-icon.png';
             
             register_post_type('topic', array(
            	'label' => __('GS Topic'),
            	'singular_label' => __('GS Topic'),
            	'description' => __('Get Satisfaction Topic Post'),
            	'public' => true,
            	'publicly_queryable' => true,
            	'exclude_from_search' => false,            
            	'show_ui' => true, // UI in admin panel
            	'_builtin' => false, // It's a custom post type, not built in!
            	'_edit_link' => 'post.php?post=%d',
            	'capability_type' => 'post',
            	'hierarchical' => false,
            	'rewrite' => array("slug" => "topic"), // Permalinks format
            	'supports' => array('title','author', 'editor'),
                'menu_icon' => $icon_url,
                'menu_position' => 2
            ));
        }

    	/**
    	 * template_redirect() - register template redirect for topic
    	 */
    	function template_redirect()
    	{
            global $wp;
            global $wp_query;
            if ($wp->query_vars["post_type"] == "topic")
            {
            	// Let's look for the property.php template file in the current theme
            	if (have_posts())
            	{
            		include(TEMPLATEPATH . '/topic.php');
            		die();
            	}
            	else
            	{
            		$wp_query->is_404 = true;
            	}
            }
    	}        

    	/**
    	 * get_satisfaction_install() - create plugin database tables, setup options
    	 *
    	 * This function installs the plugin's database tables, setup option for
    	 * the plugin
    	 *
    	 * @param void      none
    	 * @return void     none  	 
    	 */
    	function get_satisfaction_install() {
    		update_option('get_satisfaction.db_version', GET_SATISFACTION_DB_VERSION);
    		
    		// Create Get Satisfaction Topic
    		$id = wp_create_category('Topic', 0);
    		update_option('get_satisfaction.topic_category_id', $id);
    	}
    	
    	/**
    	 * get_satisfaction_uninstall() - removes plugin database tables, setup options
    	 *
    	 * This function uninstalls the plugin's database tables, setup option for
    	 * the plugin
    	 *
    	 * @param void      none
    	 * @return void     none  	 
    	 */	
    	function get_satisfaction_uninstall() {
    		delete_option('get_satisfaction.db_version');
    		delete_option('get_satisfaction.topic_category_id');
    	}
    	
        /**
         * Topic Editor
         */    	
        function add_topic_editor() {
        	add_meta_box(
        		'normal', // id of the <div> we'll add
        		'Topic',
        		array(&$this, 'topic_editor'), // callback function that will echo the box content
        		'topic' // where to add the box: on "post", "page", or "link" page
        	);
        }

        /**
         * Render Topic Editor View
         */        
        function topic_editor() {
            include('views/topic-editor.php');
        }
        
        /**
         * Save Topic Post Data
         */
        function save_topic_editor_postdata($post_id) {
            // verify this is an authorized request    
            if ( !wp_verify_nonce( $_POST['topic_noncename'], 'topic_editor_post')) {
                return $post_id;
            }
            
            // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
            // to do anything
            if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
                return $post_id;
            
            
            // Check permissions
            if ( 'page' == $_POST['post_type'] ) {
                if (!current_user_can( 'edit_page', $post_id))
                   return $post_id;
            } else {
                if (!current_user_can( 'edit_post', $post_id))
                    return $post_id;
            }
            
            if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
                return $post_id;                
            }
            
            if ('topic' == $_POST['post_type']) {
                // Process Topic Editor Form and Create Topic      
                $topic = array();     
                $topic['subject'] = $_POST['post_title'];
                $topic['additional_detail'] = $_POST['content'];
                $topic['company_domain'] = $_POST['company_domain'];
                $topic['style'] = $_POST['topic_style'];

                // TODO : Display Error Message and Add Better Error Handling
                $status = GetSatisfactionServiceApi::create_topic($topic);    
                if ($status['response_code'] == 200 || $status['response_code'] == 201) {
                    // Successfully create feed
                    $xml_feed = new XML_Feed_Parser($status['body']);

                    $items = array();
                    foreach ($xml_feed as $entry) {
                        $item = fix_atom_entry($entry, 'topic');
                        array_push($items, $item);
                    }
                    $topic = $items[0];
                    $topic_id = $topic['id'];
                    
                    $company_url = $topic['company_url'];
                    $company_id = GetSatisfactionServiceApi::get_company_id($company_url);                    
                    GetSatisfactionServiceApi::update_topic($post_id, $company_id, $topic_id);
                } else {
                    // Topic Creation Failed Remove the Post
                    wp_delete_post($post_id, true);         
                    
                    // TODO : Add better error handling to take user back to post-new with post_type=topic
                    //        and display a error message
                    
                    // wp_redirect(get_option('siteurl') . '/wp-admin/post-new.php?post_type=topic&message=10');
                    // add_filter('redirect_post_location', array (&$this, 'error_message'));
                }               
            }
        }

        function error_message($loc) {
            return add_query_arg('error_message', 123, $loc);
        }                  

        function add_all_post_type($qv) {
        	if (isset($qv['feed']))
        		$qv['post_type'] = get_post_types();
        	return $qv;
        }
        
         
    } // GetSatisfaction
}

/* Instantiated Plugin */
if (class_exists('GetSatisfaction')) { 
    $getsatisfaction = new GetSatisfaction();
}
?>